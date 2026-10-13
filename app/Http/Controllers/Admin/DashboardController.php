<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\QuoteRequest;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Cards
        $totalPipelineValue = Quotation::sum('selling_price');
        $activeRfqs = QuoteRequest::whereIn('status', ['new', 'reviewing'])->count();
        $newLeads = QuoteRequest::where('status', 'new')->count();
        $openJobs = JobPosting::where('is_active', true)->count();
        $newApplicants = JobApplication::where('status', 'new')->count();

        // Lead Funnel Data
        $funnelData = [
            'new' => QuoteRequest::where('status', 'new')->count(),
            'reviewing' => QuoteRequest::where('status', 'reviewing')->count(),
            'quoted' => QuoteRequest::where('status', 'quoted')->count(),
            'accepted' => QuoteRequest::where('status', 'accepted')->count(),
        ];

        // Regional Demand Split (Egypt vs KSA)
        $egyptCount = QuoteRequest::whereHas('country', fn($q) => $q->where('iso2', 'EG'))->count();
        $ksaCount = QuoteRequest::whereHas('country', fn($q) => $q->where('iso2', 'SA'))->count();

        // Monthly Revenue vs Profit (last 6 months)
        $monthlyData = Quotation::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(selling_price) as revenue'),
            DB::raw('SUM(factory_cost) as cost'),
            DB::raw('SUM(profit_amount) as profit')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top Requested Items — most queried products & services
        $topProducts = QuoteRequest::query()
            ->whereNotNull('product_id')
            ->with('product')
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->product?->getTranslated('title') ?: __('Product'),
                'total' => (int) $row->total,
            ]);

        $topServices = QuoteRequest::query()
            ->whereNotNull('service_id')
            ->with('service')
            ->select('service_id', DB::raw('COUNT(*) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->service?->getTranslated('title') ?: __('Service'),
                'total' => (int) $row->total,
            ]);

        $topItems = $topProducts
            ->merge($topServices)
            ->sortByDesc('total')
            ->take(8)
            ->values();

        // HR Funnel — by status
        $hrFunnel = JobApplication::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // HR Funnel — by position (openings with applicants)
        $hrByPosition = JobPosting::query()
            ->withCount('applications')
            ->having('applications_count', '>', 0)
            ->orderByDesc('applications_count')
            ->limit(6)
            ->get()
            ->map(fn ($posting) => [
                'label' => $posting->getTranslated('title') ?: __('Position'),
                'total' => (int) $posting->applications_count,
            ]);

        $recentLeads = QuoteRequest::with(['country', 'service', 'product'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalPipelineValue', 'activeRfqs', 'newLeads',
            'openJobs', 'newApplicants', 'funnelData',
            'egyptCount', 'ksaCount', 'monthlyData',
            'topItems', 'hrFunnel', 'hrByPosition', 'recentLeads'
        ));
    }
}
