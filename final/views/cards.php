<!-- Room count -->
<div class="card">
    <div class="card-header">
        Series
    </div>
    <div class="card-body">
        <p class="count">We currently have</p>
        <h2><?= $nbr_rooms ?></h2>
        <p>rooms listed!</p>
        <a href="/DDWT19/week2/add/" class="btn btn-primary">Add yours!</a>
        <-- Hier moeten we nog zorgen dat deze knop het alleen doet als iemand de roll Owner heeft -->
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
        <p>active users</p>
        <a href="/DDWT19/week2/add/" class="btn btn-primary">Join now</a>
        <-- hier kunnen we ook mooi doen met een division tussen Owners en Users -->
    </div>
</div>