<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wallet Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f9; }
        .wallet-container { max-width: 450px; margin: 50px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .balance-box { background: #007bff; color: white; padding: 20px; border-radius: 10px; margin-bottom: 25px; }
    </style>
</head>
<body>

<div class="wallet-container text-center">
    <h2 class="mb-4">My Wallet</h2>

    <div class="balance-box">
        <p class="mb-1 text-uppercase small">Available Balance</p>
        <h1 class="fw-bold">
            <?php 
 
                if (isset($data['balance']->balance)) {
                    echo number_format($data['balance']->balance, 2);
                } elseif (isset($data['balance']['balance'])) {
                    echo number_format($data['balance']['balance'], 2);
                } else {
                    echo "0.00";
                }
            ?> 
            <small>EGP</small>
        </h1>
    </div>

    <div class="text-start">
        <label class="form-label fw-bold">Add Balance</label>
        <form action="?url=walletController/deposit" method="POST">
            <div class="input-group mb-3">
                <span class="input-group-text">EGP</span>
                <input type="number" 
                       name="amount" 
                       class="form-control" 
                       placeholder="Enter amount" 
                       step="0.01" 
                       required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Confirm Deposit</button>
        </form>
    </div>

    <div class="mt-4">
        <a href="?url=pages/index" class="text-muted small text-decoration-none">← Back to Home</a>
    </div>
</div>

</body>
</html>