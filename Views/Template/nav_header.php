<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media(); ?>/img/avatar.png" alt="User Image" style="width: 35px">
        <div>
          <p class="app-sidebar__user-name">Star Seguimiento</p>
          <p class="app-sidebar__user-designation"></p>
        </div>
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item" href="<?= base_url() ?>inicio"><i class="app-menu__icon fa fa-terminal"></i><span class="app-menu__label">Inicio</span></a></li>

        <li><a class="app-menu__item" href="<?php base_url() ?>logout"><i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Logout</span></a></li>
      </ul>
    </aside>