<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            
            <a class="nav-link" href="dashboard_admin">
                <div class="sb-nav-link-icon"><i class="fa fa-dashboard blue-color" style="color:#355bf3"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Menu</div>
                <a class="nav-link" href="/pengurus/pendaftara">Registrasi</a>
                <a class="nav-link" href="/pengurus/pastor_note">Saat Teduh</a>
                <a class="nav-link" href="/pengurus/dashboard_timmultimedia">Event dan Carousel</a>
                <a class="nav-link" href="/pengurus/dashboard_popup" >Popup Ads</a>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fa fa-home fa-fw" style="color:#2956d1"></i></div>
                        Ibadah Raya
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/materi_kotbah">Materi Kotbah</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/link_ibadah">Link Ibadah Raya</a>
                    </nav>     
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                    <div class="sb-nav-link-icon"><i class='fab fa-teamspeak' style='color:#4318dc'></i></div>
                        Tim Besuk
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/dashboard_timbesuk">Data Kunjungan Jemaat</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/kunjungan">Kunjungan Jemaat</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                    <div class="sb-nav-link-icon"><i class="fa fa-home fa-fw" style="color:#2956d1"></i></div>
                        Life Group
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/materi_komsel">Materi Life Group</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/list_komsel">Daftar Life Group</a>
                    </nav>     
                </div>
                
                
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts4">
                    <div class="sb-nav-link-icon"><i class="fa fa-home fa-fw" style="color:#2956d1"></i></div>
                        Youth
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/youth_gallery">Youth Gallery</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/youth_program">Youth Program</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/pengurus/youth_schedule">Youth Schedule</a>
                    </nav>     
                </div>

        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as: {{ Auth::user()->name }}</div>
    </div>
</nav>

