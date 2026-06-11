<style>
    #sidebar{
        width: 0;
        height: 100vh;
        background: var(--bs-light);
        overflow-x: hidden;
        overflow-y: auto;
        transition: all 0.2s ease-in-out;
    }
    #sidebar.width{
        width: 225px;
    }
    @media(max-width: 768px){
        #sidebar{
            position: fixed;
            top: 0;
            left: 0;
            width: 225px;
            z-index: 1000;
        }
        #sidebar.width{
            width: 0;
        }
    }
    #sidebar .sidebar-img-head{
        color: var(--bs-light);
        text-decoration: none;
        white-space: nowrap;
        width: 100%;
        height: 100px;
        padding: 8px 12px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: end;
        gap: 2px;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/vector-1772508123712-5c812f0148d5?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center/cover;
    }
    #sidebar .link{
        padding: 8px 12px;
        border-radius: 5px;
        color: var(--bs-dark);
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease-in-out;
        display: flex;
        gap: 8px;
    }
    #sidebar .link:hover{
        color: var(--bs-primary);
        background: var(--bs-secondary);
    }
    #sidebar .page.on{
        color: var(--bs-light);
        background: var(--bs-primary);
    }
    #sidebar .page i.bi-chevron-down{
        margin-left: auto;
        transition: all 0.2s ease-in-out;
    }
    #sidebar .page i.bi-circle-fill{
        font-size: 7px;
    }
    #sidebar .page.on i.bi-chevron-down{
        transform: rotate(180deg);
    }
    #sidebar .page-item.on{
        color: var(--bs-primary);
        position: relative;
    }
    #sidebar .page-item.on::after{
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        width: 5px;
        height: 50%;
        background: var(--bs-primary);
        border-radius: 5px;
    }
    #content{
        flex: 1;
        overflow-x: hidden;
        overflow-y: auto;
        background: rgba(var(--bs-body-bg-rgb), 0.8);
    }
    #content .content-wrapper{
        max-width: var(--bs-breakpoint-xl);
    }
    #navbar .navbar-wrapper{
        border: var(--bs-border-width) solid var(--bs-border-color);
        border-radius: var(--bs-border-radius);
        background: rgba(var(--bs-body-bg-rgb), 1);
        padding: 10px;
    }
</style>
