{include file="partials/header.html.tpl"}
<div class="container" id="chart">
    <div class="row row-header">
        <div class="col">
            <header>Account</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-3">
            <div class="list-group">
                <a class="list-group-item" href="{$baseURL}developer/applications">Applications</a>
                <a class="list-group-item active" href="{$baseURL}developer/account">Account</a>
                <a class="list-group-item" href="{$baseURL}developer/logout">Logout</a>
            </div>
        </div>
        <div class="col-9">
            {if isset($message)}
            <div class="alert alert-primary" role="alert">
                {$message}
            </div>
            {/if}
            <form action="details" method="post">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" class="form-control" name="email" value="{$developer.strEmail}" disabled>
                </div>
                <div class="form-group">
                    <label for="password">Password :</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="confirmation">Password confirmation :</label>
                    <input type="password" class="form-control" name="confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Update details</button>
            </form>
        </div>
    </div>
</div>
{include file="partials/footer.html.tpl"}