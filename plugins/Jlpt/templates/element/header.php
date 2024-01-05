<style>
    .fa-bell {
        width: 2rem;
    }
    .fa-bell-animation{
        animation-name: bellshake;
        animation-duration: 4s;
    }
    .off {
        animation-name: none;
    }
    @keyframes bellshake {
        0% { transform: rotate(0); }
        10% { transform: rotate(20deg); }
        20% { transform: rotate(-20deg); }
        30% { transform: rotate(15deg); }
        40% { transform: rotate(-15deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(-10deg); }
        70% { transform: rotate(5deg); }
        80% { transform: rotate(-5deg); }
        90% { transform: rotate(1deg); }
        100% { transform: rotate(0); }
    }
    .unread{
        background-color: lightgray;
    }
    #number-notification{
        color: red;
    }
    .item-notification{
        cursor: pointer;
    }
    /*#list-notifications{*/
    /*    overflow-y: scroll;*/
    /*    height: 300px !important;*/
    /*}*/
    .scroll-unread{
        overflow-y: scroll;
        height: 300px !important;
    }
</style>
<header class="bg-light border-bottom border-secondary sticky-top">
    <div class="d-flex justify-content-between align-items-center" style="height: 59px;">
        <div class="top">
            <p class="text-center mb-0 fs-4 fw-bold">YENI PARTY</p>
        </div>
        <div class="bottom me-3">
            <div class="row" >
                <div class="col">
                    <!-- Notification -->
                    <div class="dropdown bg-white rounded p-2">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-bell fa-lg"></i>
                            <?php if (!empty($count_list_notification)) { ?>
                                <?php if($count_list_notification > 30){
                                    $count_list_notification = "30+";
                                }?>
                                <span id="number-notification"  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white"><?= $count_list_notification ?></span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu" id="list-notifications">
                            <div class="scroll-unread">

                            </div>
                            <?php if (!empty($count_list_notification)) { ?>
                                <li id="view_all" style="text-align: center"><span class="dropdown-item" style="color: #0b5ed7;cursor: pointer;text-decoration: underline">View All</span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Notification -->
                </div>
                <div class="col">
                    <div class="dropdown bg-white rounded p-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    $( document ).ready(function() {

    });
</script>
