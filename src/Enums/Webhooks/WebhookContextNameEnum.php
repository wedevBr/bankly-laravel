<?php

namespace WeDevBr\Bankly\Enums\Webhooks;

enum WebhookContextNameEnum: string
{
    case ACCOUNT = 'Account';
    case AUTHORIZATION = 'Authorization';
    case BUSINESS = 'Business';
    CASE BOLETO = 'Boleto';
    case CARD = 'Card';
    case DICT = 'Dict';
    case CREDIT = 'Credit';
    case CUSTOMER = 'Customer';
    case DOCUMENT = 'Document';
    case INVOICE = 'Invoice';
    case PARTNER = 'Partner';
    case PAYMENT = 'Payment';
    case PIX = 'Pix';
    case POCKET = 'Pocket';
    case SLC = 'SLC';
    case TED = 'Ted';
}
