<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <form action="{{route('plan.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="">Plan Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Name">
            </div>
            <div class="form-goup">
                <label for="">Amount</label>
                <input type="number" name="amount" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <label for="">Currency</label>
                <input type="text" name="currency" placeholder="Enter Currency">
            </div>
            <div class="form-group">
                <label for="">Interval Count</label>
                <input type="number" name="interval_count" placeholder="Enter Interval Count">
            </div>
            <div class="form-group">
                <label for="">Billing Period</label>
                <select name="billing_period" id="">
                    <option disabled selected>Choose Billing Method</option>
                    <option value="week">Weekly</option>
                    <option value="month">Monthly</option>
                    <option value="year">Yearly</option>
                </select>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>