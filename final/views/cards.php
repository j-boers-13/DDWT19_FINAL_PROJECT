<!-- Room count -->
<div class="card">
    <div class="card-body">
        <p class="count">We currently have <span class = "emphasis"> <?= $nbr_rooms ?> </span> rooms listed. </p>
        <div class = "pd-15">
        <a href="/DDWT19_FINAL_PROJECT/final/add/" class="btn btn-success">Add yours!</a>
        </div>
    </div>
</div>
<!-- Users count -->
<div class="card">
    <div class="card-body">
        <p class="count">
            We currently have: <span class = "emphasis"> <?= $nbr_users ?> </span> registered users, of which
            <span class = "emphasis"> <?=$nbr_tenants?> </span> are tenants, and <span class = "emphasis"><?=$nbr_owners?>
            </span> are owners.
        </p>
        <?php if(isset($user_id)){ ?>
        <div class = "pd-15">
        <a href="/DDWT19_FINAL_PROJECT/final/register/" class="btn btn-success">Join now!</a>
        </div>
        <?php } ?>
    </div>
</div>
