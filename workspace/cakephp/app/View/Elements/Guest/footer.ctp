<footer class="pt-3 mt-4 text-muted border-top <?php echo ($this->request->params['action'] === 'login' || $this->request->params['action'] === 'register') ? 'd-none' : ''; ?>">
    &copy; <?php echo h($title); ?> <?php echo date('Y'); ?>
</footer>