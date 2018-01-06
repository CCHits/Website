{include file="partials/header.html.tpl"}
<div class="container" id="chart">
    <div class="row row-header">
        <div class="col">
            <header>Developer sign up</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-12">
            {if isset($message)}
            <div class="alert alert-primary" role="alert">
                {$message}
            </div>
            {/if}
            <form method="post" action="{$baseURL}developer/signup">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="confirmation">Confirm password</label>
                    <input type="password" class="form-control" name="confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Sign up</button>
            </form>
        </div>
    </div>
</div>
{include file="partials/footer.html.tpl"}