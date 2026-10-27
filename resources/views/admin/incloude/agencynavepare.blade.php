<div class="navbar-container" style="margin-top: 7px">
  <nav class="navbar custom-navbar">
      <!-- Logo -->
      <div class="logo-container" style="width: 59px; height: 59px; border-radius: 10px; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(99,102,241,.35); overflow: hidden;">
        <img src="{{ asset('assets/logo/favicon.png') }}" alt="شعار النظام" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
    <a class="navbar-brand" href="#"></a>
    <ul class="navbar-nav nav-links-horizontal">
      @foreach ($links as $link)
        <li class="nav-item">
          <a 
            class="nav-link {{ request()->routeIs($link['active_pattern']) ? 'active' : '' }}"
            href="{{ $link['route'] }}">
            {{ $link['title'] }}
          </a>
        </li>
      @endforeach
    </ul>
    
    
  

    <!-- Dropdown في أقصى الشمال -->
    <ul class="dropdown-left">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
              إجراءات سريعة
          </a>
          <div class="dropdown-menu">
              @foreach ($menu as $menue)
                  <a class="dropdown-item" href="{{ $menue['route'] }}">
                      <!-- أيقونة مناسبة حسب العنوان -->
                      @if (Str::contains($menue['title'], 'مؤسسة'))
                          <i class="fas fa-building"></i>
                      @elseif (Str::contains($menue['title'], 'باقة'))
                          <i class="fas fa-box"></i>
                      @elseif (Str::contains($menue['title'], 'اشتراك'))
                          <i class="fas fa-user-check"></i>
                      @else
                          <i class="fas fa-cog"></i>
                      @endif
                      {{ $menue['title'] }}
                  </a>
              @endforeach
          </div>
      </li>
  </ul>
  
    @if (!request()->routeIs('admin.agencies.index'))
    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm mr-10 back-btn">
      رجوع  
    </a>
@endif


  </nav>
</div>

<link rel="stylesheet" href="{{ asset('assets/agency/navbar.css') }}"> 
<script src="{{ asset('assets/agency/navbar.js') }}"></script> 
