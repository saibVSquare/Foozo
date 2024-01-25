<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        @foreach($plans as $plan)
        <div>
            <h1>Price : {{$plan->price/100 }}$  <span>/{{$plan->billing_method}}</span></h1>
            <h2>{{$plan->name ?? ''}}</h2>
            <ul class="feature-list">
                <li>5GB Disk Space</li>
                <li>10 Domain Names</li>
                <li>5 E-Mail Address</li>
                <li>Fully Support</li>
            </ul>
            <a href="{{route('plan.checkout',$plan->plan_id)}}" class="pricing-action l-amber">Choose plan</a>
        </div>
        @endforeach
    </div>
</body>

</html>