<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src='<?php echo ProfileController::get_PP(); ?>' class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo ProfileController::get_name(); ?></p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- search form (Optional) -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li >
              <a href="{{URL::route('Announcement-home-announcement')}}" data-step="1" data-intro="show News Feed" data-position='right'>
                <i class='fa fa-home'></i> <span >News Feed<span>
                </a>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
     <script type="text/javascript">
     
     var x=$(window).height()-$('.main-header').height();

     $('.sidebar').slimScroll({
           railVisible: true,
           position: 'right',
           railBorderRadius: 0,
           borderRadius:0,
           size:10,
           height:x,
           railVisible: true,
           color:color,
           railVisible: true,
           railOpacity: 0.3,
           railColor: color,
           railBorderRadius: 0,
           opacity:0.6
           

      });
     
     </script>