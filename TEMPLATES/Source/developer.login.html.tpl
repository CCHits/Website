{include file="partials/header.html.tpl"}
<div class="container" id="chart">
    <div class="row row-header">
        <div class="col">
            <header>Developer login</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-12">
            {if isset($message)}
            <div class="alert alert-primary" role="alert">
                {$message}
            </div>
            {/if}
            <form method="post" action="{$baseURL}developer/session">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>
    </div>
</div>
{include file="partials/footer.html.tpl"}