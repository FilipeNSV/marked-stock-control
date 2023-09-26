<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController
{
  public function purchaseTransaction($request)
  {
    return Transaction::purchaseTransaction($request);
  }

  public function salesTransaction($request)
  {
    return Transaction::salesTransaction($request);
  }

  public function listTransaction()
  {
    return Transaction::listTransaction();
  }
}