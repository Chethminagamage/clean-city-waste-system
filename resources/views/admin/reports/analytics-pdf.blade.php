<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Collector Efficiency Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #16a34a;
            font-size: 24px;
            margin: 0;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #16a34a;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        table th {
            background: #16a34a;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
        }
        
        table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .rank {
            font-weight: bold;
            text-align: center;
        }
        
        .efficiency-score {
            font-weight: bold;
        }
        
        .efficiency-excellent { color: #16a34a; }
        .efficiency-good { color: #84cc16; }
        .efficiency-average { color: #eab308; }
        .efficiency-poor { color: #ef4444; }
        
        .status-active { 
            color: #16a34a; 
            font-weight: bold;
        }
        
        .status-inactive { 
            color: #ef4444; 
            font-weight: bold;
        }
        
        .rating {
            color: #f59e0b;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Clean City Waste Management System</h1>
        <h2>Collector Efficiency Analytics Report</h2>
        <p><strong>Report Period:</strong> {{ $dateRange['from'] }} to {{ $dateRange['to'] }} ({{ $timeRange }} days)</p>
        <p><strong>Generated On:</strong> {{ $generatedAt->format('M d, Y H:i:s') }}</p>
        @if($collectorId)
            <p><strong>Filtered by Collector ID:</strong> {{ $collectorId }}</p>
        @endif
    </div>

    <!-- Overview Statistics -->
    <div class="section">
        <h2>Overview Statistics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $overviewStats['active_collectors'] ?? 0 }}</div>
                <div class="stat-label">Active Collectors</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $overviewStats['completed_reports'] ?? 0 }}</div>
                <div class="stat-label">Reports Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($overviewStats['avg_completion_rate'] ?? 0, 1) }}%</div>
                <div class="stat-label">Avg Completion Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $overviewStats['avg_response_time'] ?? 'N/A' }}</div>
                <div class="stat-label">Avg Response Time</div>
            </div>
        </div>
    </div>

    <!-- Collector Performance Ranking -->
    <div class="section">
        <h2>Collector Performance Ranking</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Collector Name</th>
                    <th>Efficiency Score</th>
                    <th>Completion Rate</th>
                    <th>Avg. Time</th>
                    <th>Rating</th>
                    <th>Collections</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($collectorMetrics as $index => $collector)
                <tr>
                    <td class="rank">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $collector->name }}</strong>
                        @if($collector->location)
                            <br><small>{{ $collector->location }}</small>
                        @endif
                    </td>
                    <td class="efficiency-score
                        @if($collector->efficiency_score >= 80) efficiency-excellent
                        @elseif($collector->efficiency_score >= 60) efficiency-good
                        @elseif($collector->efficiency_score >= 40) efficiency-average
                        @else efficiency-poor
                        @endif
                    ">
                        {{ number_format($collector->efficiency_score, 1) }}%
                    </td>
                    <td>{{ number_format($collector->completion_rate, 1) }}%</td>
                    <td>{{ $collector->avg_completion_time ?? 'No data' }}</td>
                    <td class="rating">
                        @if($collector->avg_rating)
                            {{ number_format($collector->avg_rating, 1) }}/5 ★
                        @else
                            No ratings
                        @endif
                    </td>
                    <td>
                        {{ $collector->waste_reports_count }} completed
                        @if($collector->pending_count > 0)
                            <br><small>{{ $collector->pending_count }} pending</small>
                        @endif
                    </td>
                    <td class="{{ $collector->status === 1 ? 'status-active' : 'status-inactive' }}">
                        {{ $collector->status === 1 ? 'Active' : 'Inactive' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Performance Trends (if available) -->
    @if(count($performanceTrends) > 0)
    <div class="section page-break">
        <h2>Performance Trends (Last {{ $timeRange }} Days)</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reports Assigned</th>
                    <th>Reports Completed</th>
                    <th>Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceTrends as $trend)
                <tr>
                    <td>{{ $trend['date'] }}</td>
                    <td>{{ $trend['assigned'] }}</td>
                    <td>{{ $trend['completed'] }}</td>
                    <td>{{ $trend['completion_rate'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Summary & Recommendations -->
    <div class="section">
        <h2>Summary & Recommendations</h2>
        @php
            $topPerformer = $collectorMetrics->first();
            $avgEfficiency = $collectorMetrics->avg('efficiency_score');
            $totalCollectors = $collectorMetrics->count();
            $activeCollectors = $collectorMetrics->where('status', 1)->count();
        @endphp
        
        <table>
            <tr>
                <td><strong>Top Performer:</strong></td>
                <td>{{ $topPerformer->name ?? 'N/A' }} ({{ number_format($topPerformer->efficiency_score ?? 0, 1) }}% efficiency)</td>
            </tr>
            <tr>
                <td><strong>Average Team Efficiency:</strong></td>
                <td>{{ number_format($avgEfficiency, 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Collector Utilization:</strong></td>
                <td>{{ $activeCollectors }} out of {{ $totalCollectors }} collectors active</td>
            </tr>
            <tr>
                <td><strong>Recommendations:</strong></td>
                <td>
                    @if($avgEfficiency < 60)
                        Consider additional training for collectors with low efficiency scores.
                    @elseif($avgEfficiency < 80)
                        Good overall performance. Focus on improving response times.
                    @else
                        Excellent team performance. Maintain current standards.
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This report was automatically generated by the Clean City Waste Management System.</p>
        <p>For questions about this report, please contact the system administrator.</p>
    </div>
</body>
</html>
