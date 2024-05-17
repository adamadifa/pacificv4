@can(['targetkomisi.index'])
   <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation">
         <a href="{{ route('targetkomisi.index') }}" class="nav-link {{ request()->is('targetkomisi') ? 'active' : '' }}">
            <i class="tf-icons ti ti-target-arrow ti-md me-1"></i> Target Komisi
         </a>
      </li>
      <li class="nav-item" role="presentation">
         <a href="#" class="nav-link {{ request()->is('ratiokomisidriverhelper') ? 'active' : '' }}">
            <i class="tf-icons ti ti-files ti-md me-1"></i> Ratio Komisi Driver Helper
         </a>
      </li>

   </ul>
@endcan
