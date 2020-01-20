<!-- Room count -->

<div class="card">
    <div class="card-body">
        <p class="count"> There are <span class = "emphasis"> <?= $nbr_rooms ?> </span> rooms listed. </p>
        <div class = "pd-15">
        <a href="/DDWT19_FINAL_PROJECT/final/add/" class="btn btn-success">Add yours!</a>
        </div>
    </div>
</div>
<!-- Users count -->
<div class="card">
    <div class="card-body">
        <p class="count">
        There are:
        <span class = "emphasis"> <?= $nbr_users ?> </span> registered users
        </p>
        <p class="count">
        (<span class = "emphasis"> <?=$nbr_tenants?> </span> tenants / <span class = "emphasis"><?=$nbr_owners?>
        </span> owners  )
        </p>
        <?php if(isset($user_id)){ ?>
        <div class = "pd-15">
        <a href="/DDWT19_FINAL_PROJECT/final/register/" class="btn btn-success">Join now!</a>
        </div>
        <?php } ?>
    </div>
</div>
