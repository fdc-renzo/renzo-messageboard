<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold"><?php echo h($page).' '.$firstName.'!'; ?></h1>
        <p class="col-md-8 fs-4"><?php echo h($message); ?></p>
        <a  href="<?php echo $this->Html->url(['controller' => 'Home', 'action' => 'main']); ?>" class="btn btn-primary btn-lg">Home</a>
    </div>
</div>