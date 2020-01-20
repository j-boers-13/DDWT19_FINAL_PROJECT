<!-- Room count -->
<!-- Room count -->
<div class="card">
    <div class="card-body">
        <p class="count">We currently have</p>
        <h2><?= $nbr_rooms ?></h2>
        <p>rooms listed.</p>
        <a href="/DDWT19_FINAL_PROJECT/final/add/" class="btn btn-primary">Add yours!</a>
    </div>
</div>
<!-- Users count -->
<div class="card">
    <div class="card-body">
        <p class="count">We currently have
        <div class = "emphasis"> <?= $nbr_users ?> </div>
        registered users, of which
        <div class = "emphasis"><?=$nbr_tenants?> </div>
        are tenants, and
        <div class = "emphasis"><?=$nbr_owners?> </div>
        are owners. </p>
        <?php if(isset($user_id)){ ?>
        <a href="/DDWT19_FINAL_PROJECT/final/register/" class="btn btn-primary">Join now!</a>
        <?php } ?>
    </div>
</div>
