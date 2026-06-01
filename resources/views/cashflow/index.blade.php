<x-app-layout title="Cash Flow - Alur Confused" icon='<i data-lucide="wallet" class="me-3"></i>Cash Flow'>

    <div class="container-fluid py-4">

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Pemasukan</h6>
                        <h3 class="text-success mb-0">
                            Rp {{ number_format($pemasukan, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Pengeluaran</h6>
                        <h3 class="text-danger mb-0">
                            Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Profit</h6>
                        <h3 class="text-primary mb-0">
                            Rp {{ number_format($profit, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tanggal Mulai</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                class="form-control" 
                                value="{{ request('start_date') }}"
                            >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tanggal Selesai</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                class="form-control" 
                                value="{{ request('end_date') }}"
                            >
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('cashflow.exportPdf', request()->query()) }}" class="btn btn-info w-100 text-white">
                                <i data-lucide="download" class="me-2 d-inline-block" style="width: 16px; height: 16px;"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 5%">No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Sumber</th>
                                <th>Omzet</th>
                                <th>HPP</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}</td>
                                <td>{{ $row['jenis'] }}</td>
                                <td>{{ $row['sumber'] }}</td>

                                <td>
                                    Rp {{ number_format($row['omzet'],0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($row['hpp'],0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($row['profit'],0,',','.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('input[type="date"]').on('change', function () {
                    $('#filterForm').submit();
                });
            });
        </script>
    @endpush

</x-app-layout>