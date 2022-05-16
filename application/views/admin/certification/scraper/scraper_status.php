<style lang="scss">
    .table-title {
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .table-content {
        word-break: break-all;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">會員爬蟲列表</h1>
        </div>
    </div>
    <scraper-status></scraper-status>
</div>
<script>
    const v = new Vue({ el: '#page-wrapper' })
</script>
