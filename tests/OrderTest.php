<?php
// Copyright 2026 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Order;
use Casdoor\Product;

class OrderTest extends TestBase
{
    private function addProduct(string $name): Product
    {
        $product              = new Product();
        $product->owner       = $this->client->organizationName;
        $product->name        = $name;
        $product->createdTime = date('c');
        $product->displayName = $name;
        $product->image       = 'https://cdn.casbin.org/img/casdoor-logo_1185x256.png';
        $product->description = 'Casdoor Website';
        $product->tag         = 'auto_created_product_for_plan';
        $product->quantity    = 999;
        $product->state       = 'Published';
        $product->providers   = ['provider_payment_dummy'];
        $product->price       = 1.0;
        $product->currency    = 'USD';
        $this->assertTrue($this->client->addProduct($product));
        return $product;
    }

    public function testOrder(): void
    {
        $productName = 'OrderProduct_' . bin2hex(random_bytes(4));
        $product     = $this->addProduct($productName);

        $order               = new Order();
        $order->owner        = $this->client->organizationName;
        $order->name         = 'Order_' . bin2hex(random_bytes(4));
        $order->createdTime  = date('c');
        $order->displayName  = $order->name;
        $order->products     = [$productName];
        $order->productInfos = [['owner' => $order->owner, 'name' => $productName, 'displayName' => $productName, 'price' => 1, 'currency' => 'USD', 'quantity' => 1]];
        $order->user         = 'admin';
        $order->price        = 1.0;
        $order->currency     = 'USD';
        $order->state        = 'Created';
        $this->assertTrue($this->client->addOrder($order));

        $this->assertContains($order->name, array_column($this->client->getOrders(), 'name'));
        $this->assertContains($order->name, array_column($this->client->getUserOrders('admin'), 'name'));
        $this->assertSame($order->name, $this->client->getOrder($order->name)['name']);

        $order->message = 'Updated order message';
        $this->assertTrue($this->client->updateOrder($order));
        $this->assertSame('Updated order message', $this->client->getOrder($order->name)['message']);

        $this->assertTrue($this->client->cancelOrder($order->name));
        $this->assertTrue($this->client->deleteOrder($order));
        $this->assertNull($this->client->getOrder($order->name));

        $this->client->deleteProduct($product);
    }

    public function testOrderPay(): void
    {
        $productName = 'OrderPayProduct_' . bin2hex(random_bytes(4));
        $product     = $this->addProduct($productName);

        $order = $this->client->placeOrder([['name' => $productName, 'quantity' => 1]], 'admin');
        $this->assertNotEmpty($order['name']);

        $payment = $this->client->payOrder($order['name'], 'provider_payment_dummy');
        $this->assertNotEmpty($payment);

        $order = $this->client->buyProduct($productName, 'provider_payment_dummy', 'admin');
        $this->assertNotEmpty($order['name']);

        $this->client->deleteProduct($product);
    }
}
