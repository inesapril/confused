<style>
/* ==========================================================================
   SHARED DATATABLE & LIST PAGE STYLING (Industrial Minimalist)
   ========================================================================== */

.swal-popup-poppins {
    font-family: 'Poppins', sans-serif !important;
}

/* Global Table Transparent Utilities */
.table-transparent {
    background: transparent !important;
}

.table-transparent th,
.table-transparent td {
    background: transparent !important;
    border-bottom: 1px solid var(--border-color-light) !important;
    padding: 12px 14px !important;
}

.table-transparent thead th {
    border-bottom: 2px solid var(--border-color) !important;
}

/* DataTable Wrapper Alignments */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: var(--color-secondary) !important;
    margin-top: 15px;
}

/* Re-styling Pagination Buttons (Flat Minimalist) */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    font-family: 'Poppins', sans-serif;
    font-weight: 600 !important;
    font-size: 12px !important;
    border-radius: 4px !important;
    margin: 0 2px;
    background: #ffffff !important;
    border: 1px solid var(--border-color) !important;
    color: var(--color-foreground) !important;
    padding: 5px 12px !important;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button a {
    background: transparent !important;
    border: none !important;
    color: inherit !important;
}

/* Active Page Highlight - Brushed Steel */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--color-primary) !important;
    border: 1px solid var(--color-primary) !important;
    color: #ffffff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current a {
    color: #ffffff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
    border: 1px solid var(--color-foreground) !important;
    color: var(--color-foreground) !important;
}

/* Table Actions Button Group */
.btn-group .btn {
    font-size: 11px !important;
    font-weight: 600 !important;
    border-radius: 4px !important;
    padding: 6px 10px !important;
    transition: all 0.2s ease;
    margin-right: 4px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-group .btn.btn-warning {
    background: var(--color-warning) !important;
    border-color: var(--color-warning) !important;
    color: #ffffff !important;
}

.btn-group .btn.btn-warning:hover {
    background: #b45309 !important;
    border-color: #b45309 !important;
}

.btn-group .btn.btn-danger {
    background: var(--color-danger) !important;
    border-color: var(--color-danger) !important;
    color: #ffffff !important;
}

.btn-group .btn.btn-danger:hover {
    background: #9f1239 !important;
    border-color: #9f1239 !important;
}

.btn-group .btn.btn-info {
    background: var(--color-info) !important;
    border-color: var(--color-info) !important;
    color: #ffffff !important;
}

.btn-group .btn.btn-info:hover {
    background: #0369a1 !important;
    border-color: #0369a1 !important;
}

/* Custom Search Container */
.custom-search-container {
    position: relative;
}

.custom-search-input {
    padding-left: 40px !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    height: 44px !important;
    transition: all 0.2s ease;
}

.custom-search-input:focus {
    border-color: var(--color-foreground) !important;
    box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.08) !important;
}

.custom-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-secondary);
    pointer-events: none;
    width: 16px;
    height: 16px;
}

/* Card overrides inside Table list pages */
.card {
    background: #ffffff !important;
    border: 1px solid var(--border-color-light) !important;
    border-radius: 8px !important;
}

/* Structural Steel Table Styles */
#barangTable, .table {
    border: 1px solid var(--border-color-light) !important;
    border-collapse: collapse !important;
    background: #ffffff !important;
    font-size: 13px !important;
}

#barangTable th, .table th,
#barangTable td, .table td {
    border: 1px solid var(--border-color-light) !important;
    padding: 12px 16px !important;
    vertical-align: middle;
}

/* Crisp Concrete Headers */
#barangTable thead th, .table thead th {
    background: #f8fafc !important; /* Cool Concrete slate */
    text-align: left !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    color: var(--color-secondary) !important;
    border-bottom: 2px solid var(--border-color) !important;
}

/* Zebra Rows & Hover highlighting */
#barangTable tbody tr:nth-child(odd), .table tbody tr:nth-child(odd) {
    background: #ffffff !important;
}

#barangTable tbody tr:nth-child(even), .table tbody tr:nth-child(even) {
    background: #f8fafc !important;
}

#barangTable tbody tr:hover, .table tbody tr:hover {
    background: #f1f5f9 !important; /* Steel Hover */
}

/* Format codes, pricing columns as Courier Monospace */
#barangTable td:nth-child(2), /* Kode Barang */
#barangTable td:nth-child(5), /* Harga Jual */
#barangTable td:nth-child(6)  /* Harga Beli */ {
    font-family: 'Courier New', Courier, monospace !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    letter-spacing: 0.02em;
}
</style>
