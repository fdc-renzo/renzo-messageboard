<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo h($page); ?></h1>

    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $this->Html->url(['controller' => 'Users', 'action' => 'profile']); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise"></i>
            Back
        </a>
    </div>

</div>

<div class="row w-50">
    <div class="col-3">
        <?php if (!empty($userdata['User']['profile_url'])): ?>
            <img src="<?php echo h($userdata['User']['profile_url']); ?>" width="150" height="150" alt="Profile Image" class="img-fluid">
        <?php else: ?>
            <img src="https://i.ibb.co/pW9DJrH/blank-twitter-icon.webp" width="150" height="150" alt="Default Profile Image" class="img-fluid">
        <?php endif; ?>
    </div>

    <div class="col-9 px-4">

        <h1 class="fw-light"><?php echo $firstName. ' ' .$age; ?></h1>
        <span class="fw-light fs-15"><label class="fw-normal">Gender:</label> <?php echo ($userdata['User']['gender'] == null ? '<i>Birthdate not yet updated.</i>' : h($userdata['User']['gender']) ); ?></span><br>
        <span class="fw-light fs-15"><label class="fw-normal">Birthdate:</label> <?php echo ($userdata['User']['birthdate'] == null ? '<i>Update Birthdate.</i>' : date('F d, Y', strtotime($userdata['User']['birthdate'])) ); ?></span><br>
        <span class="fw-light fs-15"><label class="fw-normal">Joined:</label> <?php echo date('F d, Y ha', strtotime($userdata['User']['created'])); ?></span><br>
        <span class="fw-light fs-15"><label class="fw-normal">Last Login:</label> <?php echo date('F d, Y ha', strtotime($userdata['User']['last_login_time'])); ?></span>


    </div>
    
    <label class="fw-normal mt-3">My Hubby:</label>
    <p style="text-align:justify;" class="fw-light"><?php echo $userdata['User']['hubby'] == null ? '<i>No input hubby yet.</i>' : h($userdata['User']['hubby']); ?></p>
</div>

<div style="height:500px;"></div>