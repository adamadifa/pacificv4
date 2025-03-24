<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('ticket.index') }}" class="nav-link {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
            <i class="tf-icons ti ti-file-description ti-md me-1"></i>Maintenance & Penmabahan Fitur
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('ticketupdate.index') }}" class="nav-link {{ request()->is(['ticketupdate', 'ticketupdate/*']) ? 'active' : '' }}">
            <i class="tf-icons ti ti-file-description ti-md me-1"></i> Perubahan Data Transaksi
        </a>
    </li>

</ul>
