@if (auth()->user()->hasAnyPermission(['ajuanlimit.index']))
   <ul class="nav nav-tabs" role="tablist">
      @can('ajuanlimit.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('ajuanlimit.index') }}"
               class="nav-link {{ request()->is(['ajuanlimit', 'ajuanlimit/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Limit Kredit
            </a>
         </li>
      @endcan
   </ul>
@endif
