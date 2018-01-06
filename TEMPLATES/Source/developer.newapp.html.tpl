{include file="partials/header.html.tpl"}
<div class="container" id="chart">
    <div class="row row-header">
        <div class="col">
            <header>New application</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-3">
            <div class="list-group">
                <a class="list-group-item active" href="{$baseURL}developer/applications">Applications</a>
                <a class="list-group-item" href="{$baseURL}developer/account">Account</a>
                <a class="list-group-item" href="{$baseURL}developer/logout">Logout</a>
            </div>
        </div>
        <div class="col-9">
            {if isset($message)}
            <div class="alert alert-primary" role="alert">
                {$message}
            </div>
            {/if}
            <form action="{$baseURL}developer/new" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="8" required></textarea>
                </div>
                <div class="form-group">
                    <label for="url">Application homepage (link)</label>
                    <input type="url" name="url" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Create application</button>
            </form>
        </div>
    </div>
</div>
{include file="partials/footer.html.tpl"}