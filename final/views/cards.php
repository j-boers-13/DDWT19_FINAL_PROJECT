<!-- Room count -->
<!-- Room count -->
<div class="card">
    <div class="card-header">
        Series
    </div>
    <div class="card-body">
        <p class="count">We currently have</p>
        <h2><?= $nbr_rooms ?></h2>
        <p>rooms listed.</p>
        <a href="/DDWT19_FINAL_PROJECT/final/add/" class="btn btn-primary">Add yours!</a>
    </div>
</div>
<!-- Users count -->
<div class="card">
    <div class="card-header">
        Users
    </div>
    <div class="card-body">
        <p class="count">We currently have</p>
        <h2><?= $nbr_users ?></h2>
        <p>registered users, of which </p>
        <h3><?=$nbr_tenants?></h3>
        <p>are tenants, and </p>
        <h3><?=$nbr_owners?></h3>
        <p>are owners. </p>
        <a href="/DDWT19_FINAL_PROJECT/final/register/" class="btn btn-primary">Join now!</a>
    </div>
</div>
