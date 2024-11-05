<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark active" aria-current="page" href="<?php echo $this->Html->url(['controller' => 'Home', 'action' => 'main']); ?>">
                <i class="bi bi-chat-square-quote"></i>
                    Messages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo $this->Html->url(['controller' => 'Users', 'action' => 'profile']); ?>">
                <i class="bi bi-person"></i>
                    Profile
                </a>
            </li>
        </ul>


    </div>
</nav>