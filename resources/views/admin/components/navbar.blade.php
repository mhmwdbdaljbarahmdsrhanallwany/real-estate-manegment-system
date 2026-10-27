<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0B132B;">    <div class="container">

        <ul class="navbar-nav">

            @foreach($links as $link)

                <li class="nav-item">

                    <a 
                    href="{{ $link['url'] }}"
                    class="nav-link text-white 
                    {{ $link['active'] ? 'active-tab' : '' }}"
                    >

                        {{ $link['title'] }}

                    </a>

                </li>

            @endforeach

        </ul>

    </div>

</nav>
<style>
.active-tab {
    background-color: #84beb3 !important; /* الأخضر الذكي المستوحى من الشعار */
    color: #0B132B !important; /* نص داكن لضمان أعلى تباين وقراءة واضحة */
    border-radius: 8px;
    padding: 10px;
}
    
    
    
    </style>