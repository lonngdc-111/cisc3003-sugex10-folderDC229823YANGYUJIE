<?php
include 'includes/book-utilities.inc.php';

// 读取客户
$customers = readCustomers('data/customers.txt');
$selectedCustomerId = $_GET['customer_id'] ?? '';
$selectedCustomer = findCustomerById($customers, $selectedCustomerId);
$orders = $selectedCustomer ? readOrders($selectedCustomer, 'data/orders.txt') : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CISC3003 Suggested Exercise 10</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/demo-styles.css">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>

    <style>
    .sparkline-bar { display: inline-block; min-width: 62px; }
    .cover-thumb { width: 48px; height: auto; display: block; }
    .customer-link { color: #ff9800; text-decoration: underline; }
    .customer-link:hover { color: #e68900; }
    </style>
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">

    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>

    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">
            <div class="mdl-grid">

                <!-- 客户列表 -->
                <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--orange">
                        <h2 class="mdl-card__title-text">Customers</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <table class="mdl-data-table mdl-shadow--2dp">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Name</th>
                                    <th class="mdl-data-table__cell--non-numeric">University</th>
                                    <th class="mdl-data-table__cell--non-numeric">City</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <a href="?customer_id=<?php echo urlencode($customer['id']); ?>" class="customer-link">
                                            <?php echo h($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                        </a>
                                    </td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo h($customer['university']); ?></td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo h($customer['city']); ?></td>
                                    <td><span class="sparkline-bar"><?php echo h($customer['sales']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 右侧详情 -->
                <div class="mdl-grid mdl-cell--5-col">

                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Customer Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <?php if ($selectedCustomer): ?>
                                <h4><?php echo h($selectedCustomer['first_name'] . ' ' . $selectedCustomer['last_name']); ?></h4>
                                <p>Email: <?php echo h($selectedCustomer['email']); ?></p>
                                <p>University: <?php echo h($selectedCustomer['university']); ?></p>
                                <p>Address: <?php echo h($selectedCustomer['address'] . ', ' . $selectedCustomer['city'] . ', ' . $selectedCustomer['state'] . ', ' . $selectedCustomer['country'] . ', ' . $selectedCustomer['zip']); ?></p>
                                <p>Phone: <?php echo h($selectedCustomer['phone']); ?></p>
                            <?php else: ?>
                                <p>Select a customer to view details.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Order Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <table class="mdl-data-table mdl-shadow--2dp order-table">
                                <thead>
                                    <tr>
                                        <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                        <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                        <th class="mdl-data-table__cell--non-numeric">Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!$selectedCustomer): ?>
                                <?php elseif (empty($orders)): ?>
                                    <tr><td colspan="3">No orders for this customer.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <?php $cover = findBookCover($order['isbn']); ?>
                                            <?php if ($cover): ?>
                                                <img src="<?php echo h($cover); ?>" class="cover-thumb" alt="<?php echo h($order['title']); ?>">
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo h($order['isbn']); ?></td>
                                        <td><?php echo h($order['title']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </main>
</div>

<script>
$(function() {
    $('.sparkline-bar').sparkline('html', {
        type: 'bar',
        barColor: '#3f51b5',
        height: '22px',
        barWidth: 4,
        barSpacing: 1
    });
});
</script>
</body>
</html>>