 <!-- Sidebar -->
 <div class="sidebar sidebar-style-2">
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <div class="user">
                 <div class="avatar-sm float-left mr-2">
                     <img src="../assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                 </div>
                 <div class="info">
                     <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                         <span>
                             {{Auth::user()->nama}}
                             <span class="user-level">{{Auth::user()->level_user}}</span>
                             <span class="caret"></span>
                         </span>
                     </a>
                     <div class="clearfix"></div>

                     <div class="collapse in" id="collapseExample">
                         <ul class="nav">
                             <li class="sub-menu">
                                 <a href="#profile">
                                     <span class="link-collapse">My Profile</span>
                                 </a>
                             </li>
                             <li class="sub-menu">
                                 <a href="#edit">
                                     <span class="link-collapse">Edit Profile</span>
                                 </a>
                             </li>
                             <li class="sub-menu">
                                 <a href="{{ route('logout') }}">
                                     <span class="link-collapse">Log Out</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
             <ul class="nav nav-primary">
                 <li class="nav-item">
                     <a href="/dashboard">
                         <i class="fas fa-home"></i>
                         <p>Dashboard</p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a data-toggle="collapse" href="#sidebarMaster">
                         <i class="fas fa-layer-group"></i>
                         <p>Master</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="sidebarMaster">
                         <ul class="nav nav-collapse">
                             <li class="sub-menu">
                                 <a href="/user">
                                     <span class="sub-item">User</span>
                                 </a>
                             </li>
                             <li class="sub-menu">
                                 <a href="/pelanggan">
                                     <span class="sub-item">Pelanggan</span>
                                 </a>
                             </li>
                             <li class="sub-menu">
                                 <a href="/tarif-air">
                                     <span class="sub-item">Tarif Air</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 <li class="nav-item">
                     <a data-toggle="collapse" href="#sidebarAirku">
                         <i class="fas fa-tint"></i>
                         <p>Airku</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="sidebarAirku">
                         <ul class="nav nav-collapse">
                             <li class="sub-menu">
                                 <a href="/pemakaian">
                                     <span class="sub-item">Pemakaian</span>
                                 </a>
                             </li>
                             <li class="sub-menu">
                                 <a href="/pembayaran">
                                     <span class="sub-item">Pembayaran</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 <li class="nav-item">
                     <a href="/laporan">
                         <i class="fas fa-file"></i>
                         <p>Laporan</p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="/setting-global">
                         <i class="fas fa-cog"></i>
                         <p>Setting Global</p>
                     </a>
                 </li>
             </ul>
         </div>
     </div>
 </div>
 <!-- End Sidebar -->

 <div class="main-panel">