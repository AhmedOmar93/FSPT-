@include('layout/headerTheme') 
  
{{HTML::script('module/projectController.js');}}

    <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the 
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |  
  |---------------------------------------------------------|
  
  -->


  <body class="{{ProfileController::get_theme()}}">
    <div class="wrapper">
    
      @include('layout/topbar')
      
    <!--  @include('layout/sidebar')-->
     
      @include('layout/footerTheme') 