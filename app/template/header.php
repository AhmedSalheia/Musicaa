<header>
    <div class="header-top">
        <div class="h-100">
        <div class="row">
        <div class="text-left col-6">
            <a href="/index">
            <div class=" logo col-auto pt-2 pl-4 d-lg-block w-100 h-75" >
                <picture><img src="<?= IMG; ?>logo.svg" alt="logo" id="logo"></picture>
            </div></a>

        </div>

                   <div class=" col-6  text-right menu-container">

                       <a class="text-right  toggle-menu " style="cursor: pointer;" id="hamp">
                           <i></i>
                           <i></i>
                           <i></i></a>

                       <div class="menu-box" id="menu-bar">

                           <div class=" link1   text-left  ">
                               <ul class="list-unstyled  my-auto pt-2 " id="list1">
                                   <li class="<?= ($controller == 'index')? 'active':''; ?>"><a href="/index" target="" class="<?= ($controller == 'index')? 'text-primary pb-4 font-weight-bold':''; ?>"><?= $home; ?></a></li>
                                   <li class="<?= ($controller == 'about')? 'active':''; ?>"><a href="/about" target="" class="<?= ($controller == 'about')? 'text-primary pb-4 font-weight-bold':''; ?>"><?= $about; ?></a></li>
                                   <li class="<?= ($controller == 'services')? 'active':''; ?>"><a href="/services" target="" class="<?= ($controller == 'services')? 'text-primary pb-4 font-weight-bold':''; ?>"><?= $serv; ?></a></li>
                                   <li class="<?= ($controller == 'contact')? 'active':''; ?>"><a href="/contact" target="" class="<?= ($controller == 'contact')? 'text-primary pb-4 font-weight-bold':''; ?>"><?= $cont; ?></a></li>
                               </ul>
                           </div>
                       </div>

                 </div>






    </div>
        </div>


    </div>

</header>
