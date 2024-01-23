<?php

namespace WeDevBr\Bankly\Enums\Webhooks;

enum WebhookEventNameEnum: string
{
    //PIX events
    case PIX_CASH_IN_WAS_RECEIVED = 'PIX_CASH_IN_WAS_RECEIVED';
    case PIX_CASH_IN_WAS_CLEARED = 'PIX_CASH_IN_WAS_CLEARED';
    case PIX_REFUND_WAS_RECEIVED = 'PIX_REFUND_WAS_RECEIVED';
    case PIX_REFUND_WAS_CLEARED = 'PIX_REFUND_WAS_CLEARED';
    case PIX_CASHOUT_WAS_COMPLETED = 'PIX_CASHOUT_WAS_COMPLETED';
    case PIX_CASHOUT_WAS_CANCELED = 'PIX_CASHOUT_WAS_CANCELED';
    case PIX_CASHOUT_WAS_UNDONE = 'PIX_CASHOUT_WAS_UNDONE';
    case PIX_QRCODE_WAS_CREATED = 'PIX_QRCODE_WAS_CREATED';

    //Billet events
    case BOLETO_CASH_IN_WAS_RECEIVED = 'BOLETO_CASH_IN_WAS_RECEIVED';
    case BOLETO_CASH_IN_WAS_CLEARED = 'BOLETO_CASH_IN_WAS_CLEARED';
    case BOLETO_WAS_REGISTERED = 'BOLETO_WAS_REGISTERED';
    case BOLETO_WAS_CANCELLED_BY_RECIPIENT = 'BOLETO_WAS_CANCELLED_BY_RECIPIENT';
    case BOLETO_WAS_CANCELLED_BY_DEADLINE = 'BOLETO_WAS_CANCELLED_BY_DEADLINE';

    //PIX DICT events
    case PIX_CLAIM_WAS_ACKNOWLEDGED = 'PIX_CLAIM_WAS_ACKNOWLEDGED';
    case PIX_CLAIM_WAS_CONFIRMED = 'PIX_CLAIM_WAS_CONFIRMED';
    case PIX_CLAIM_WAS_COMPLETED = 'PIX_CLAIM_WAS_COMPLETED';
    case PIX_CLAIM_WAS_CANCELED = 'PIX_CLAIM_WAS_CANCELED';
    case PIX_CLAIM_WAS_REGISTERED = 'PIX_CLAIM_WAS_REGISTERED';

    //TED events
    case TED_CASH_OUT_WAS_APPROVED = 'TED_CASH_OUT_WAS_APPROVED';
    case TED_CASH_IN_WAS_RECEIVED = 'TED_CASH_IN_WAS_RECEIVED';
    case TED_CASH_IN_WAS_CLEARED = 'TED_CASH_IN_WAS_CLEARED';
    case TED_REFUND_WAS_RECEIVED = 'TED_REFUND_WAS_RECEIVED';
    case TED_REFUND_WAS_CLEARED = 'TED_REFUND_WAS_CLEARED';
    case TED_CASH_OUT_WAS_DONE = 'TED_CASH_OUT_WAS_DONE';
    case TED_CASH_OUT_WAS_REPROVED = 'TED_CASH_OUT_WAS_REPROVED';
    case TED_CASH_OUT_WAS_UNDONE = 'TED_CASH_OUT_WAS_UNDONE';
    case TED_CASH_OUT_WAS_CANCELED = 'TED_CASH_OUT_WAS_CANCELED';

    // BillPayment events
    case BILL_PAYMENT_WAS_RECEIVED = 'BILL_PAYMENT_WAS_RECEIVED';
    case BILL_PAYMENT_WAS_CREATED = 'BILL_PAYMENT_WAS_CREATED';
    case BILL_PAYMENT_WAS_CONFIRMED = 'BILL_PAYMENT_WAS_CONFIRMED';
    case BILL_PAYMENT_HAS_FAILED = 'BILL_PAYMENT_HAS_FAILED';
    case BILL_PAYMENT_WAS_CANCELLED = 'BILL_PAYMENT_WAS_CANCELLED';
    case BILL_PAYMENT_WAS_REFUSED = 'BILL_PAYMENT_WAS_REFUSED';

    // Documents events
    case DOCUMENT_WAS_RECEIVED = 'DOCUMENT_WAS_RECEIVED';
    case DOCUMENT_WAS_PROCESSED = 'DOCUMENT_WAS_PROCESSED';

    // Account events
    case ACCOUNT_WAS_CREATED = 'ACCOUNT_WAS_CREATED';
    case ACCOUNT_WAS_CLOSED = 'ACCOUNT_WAS_CLOSED';
    case ACCOUNT_WAS_LEGALLY_CLOSED = 'ACCOUNT_WAS_LEGALLY_CLOSED';
    case AMOUNT_WAS_BLOCKED = 'AMOUNT_WAS_BLOCKED';
    case AMOUNT_WAS_UNBLOCKED = 'AMOUNT_WAS_UNBLOCKED';
    case PAYMENT_ACCOUNT_WAS_LOCKED = 'PAYMENT_ACCOUNT_WAS_LOCKED';
    case PAYMENT_ACCOUNT_WAS_UNLOCKED = 'PAYMENT_ACCOUNT_WAS_UNLOCKED';

    // Card Transaction events
    case TRANSACTION_WAS_PROCESSED = 'TRANSACTION_WAS_PROCESSED';
    case TRANSACTION_WAS_REVERTED = 'TRANSACTION_WAS_REVERTED';
    case TRANSACTION_WAS_EXPIRED = 'TRANSACTION_WAS_EXPIRED';
    case CONFIRMATION_WAS_PROCESSED = 'CONFIRMATION_WAS_PROCESSED';
    case CANCELATION_WAS_PROCESSED = 'CANCELATION_WAS_PROCESSED';
    case VOUCHER_WAS_PROCESSED = 'VOUCHER_WAS_PROCESSED';
    case PRE_AUTHENTICATION_WAS_RECEIVED = 'PRE_AUTHENTICATION_WAS_RECEIVED';
    case PRE_AUTHENTICATION_CHALLENGE_WAS_REQUESTED = 'PRE_AUTHENTICATION_CHALLENGE_WAS_REQUESTED';

    // Card registration
    case CARD_WAS_ISSUED = 'CARD_WAS_ISSUED';
    case TRACKING_STATUS_CHANGED = 'TRACKING_STATUS_CHANGED';
    case CARD_STATUS_WAS_MODIFIED = 'CARD_STATUS_WAS_MODIFIED';
    case CARD_WAS_ADDED_TO_WALLET = 'CARD_WAS_ADDED_TO_WALLET';
    case CARD_WAS_REMOVED_FROM_WALLET = 'CARD_WAS_REMOVED_FROM_WALLET';

    // Credit Card Events
    case CREDIT_CARD_LIMIT_CREATED = 'CREDIT_CARD_LIMIT_CREATED';
    case CREDIT_CARD_LIMIT_APPROVED = 'CREDIT_CARD_LIMIT_APPROVED';
    case CREDIT_CARD_LIMIT_REPROVED = 'CREDIT_CARD_LIMIT_REPROVED';
    case CREDIT_CARD_ANALYSIS_COMPLETED = 'CREDIT_CARD_ANALYSIS_COMPLETED';
    case CREDIT_CARD_ANALYSIS_EXPIRED = 'CREDIT_CARD_ANALYSIS_EXPIRED';
    case CREDIT_CARD_CONTRACT_ACCEPTED = 'CREDIT_CARD_CONTRACT_ACCEPTED';
    case CREDIT_CARD_CONTRACT_BLOCKED = 'CREDIT_CARD_CONTRACT_BLOCKED';
    case CREDIT_CARD_CONTRACT_CANCELLED = 'CREDIT_CARD_CONTRACT_CANCELLED';
    case CREDIT_CARD_CONTRACT_UNBLOCKED = 'CREDIT_CARD_CONTRACT_UNBLOCKED';
    case CREDIT_CARD_LIMIT_INCREASE_REQUESTED = 'CREDIT_CARD_LIMIT_INCREASE_REQUESTED';
    case CREDIT_CARD_LIMIT_INCREASE_APPROVED = 'CREDIT_CARD_LIMIT_INCREASE_APPROVED';
    case CREDIT_CARD_LIMIT_INCREASE_ACCEPTED = 'CREDIT_CARD_LIMIT_INCREASE_ACCEPTED';
    case CREDIT_CARD_LIMIT_INCREASE_REFUSED = 'CREDIT_CARD_LIMIT_INCREASE_REFUSED';
    case CREDIT_CARD_LIMIT_REDUCTION_REQUESTED = 'CREDIT_CARD_LIMIT_REDUCTION_REQUESTED';
    case CREDIT_CARD_LIMIT_REDUCTION_APPLIED = 'CREDIT_CARD_LIMIT_REDUCTION_APPLIED';
    case INVOICE_BILLING_WAS_OVERDUE = 'INVOICE_BILLING_WAS_OVERDUE';
    case INVOICE_BILLING_WAS_PAID = 'INVOICE_BILLING_WAS_PAID';
    case CUSTOMER_BAD_CREDIT_APPLY_REQUESTED = 'CUSTOMER_BAD_CREDIT_APPLY_REQUESTED';
    case CUSTOMER_BAD_CREDIT_REMOVE_REQUESTED = 'CUSTOMER_BAD_CREDIT_REMOVE_REQUESTED';
    case CUSTOMER_BILLING_CREATED = 'CUSTOMER_BILLING_CREATED';
    case CONTRACT_CREDIT_BLOCK_REQUEST_CREATED = 'CONTRACT_CREDIT_BLOCK_REQUEST_CREATED';
    case CONTRACT_CREDIT_UNBLOCK_REQUEST_CREATED = 'CONTRACT_CREDIT_UNBLOCK_REQUEST_CREATED';
    case CONTRACT_CREDIT_CANCELLMENT_REQUESTED = 'CONTRACT_CREDIT_CANCELLMENT_REQUESTED';

    // Customer events
    case CUSTOMER_WAS_RECEIVED = 'CUSTOMER_WAS_RECEIVED';
    case CUSTOMER_IN_ANALYSIS = 'CUSTOMER_IN_ANALYSIS';
    case CUSTOMER_WAS_APPROVED = 'CUSTOMER_WAS_APPROVED';
    case CUSTOMER_WAS_REPROVED = 'CUSTOMER_WAS_REPROVED';
    case CUSTOMER_WAS_CANCELED = 'CUSTOMER_WAS_CANCELED';
    case CUSTOMER_WAS_REVOKED = 'CUSTOMER_WAS_REVOKED';
    case CUSTOMER_WAS_BLOCKED = 'CUSTOMER_WAS_BLOCKED';
    case CUSTOMER_WAS_UPDATED = 'CUSTOMER_WAS_UPDATED';

    // Business context
    case BUSINESS_WAS_RECEIVED = 'BUSINESS_WAS_RECEIVED';
    case BUSINESS_WAS_APPROVED = 'BUSINESS_WAS_APPROVED';
    case BUSINESS_WAS_REPROVED = 'BUSINESS_WAS_REPROVED';
    case BUSINESS_WAS_CANCELED = 'BUSINESS_WAS_CANCELED';

    // Invoices context
    case TRANSACTION_CREATED = 'TRANSACTION_CREATED';
    case INVOICE_CLOSED = 'INVOICE_CLOSED';
    case INVOICE_PAYMENT_OPTION_CREATED = 'INVOICE_PAYMENT_OPTION_CREATED';
    case INVOICE_PAYMENT_PROCESSED = 'INVOICE_PAYMENT_PROCESSED';

    // Pocket account context
    case POCKET_ACCOUNT_WAS_CREATED = 'POCKET_ACCOUNT_WAS_CREATED';
    case POCKET_ACCOUNT_WAS_FULLY_CLOSED = 'POCKET_ACCOUNT_WAS_FULLY_CLOSED';
    case POCKET_ACCOUNT_WAS_TECHNICALLY_CLOSED = 'POCKET_ACCOUNT_WAS_TECHNICALLY_CLOSED';
    case POCKET_ACCOUNT_SAVING_WAS_COMPLETED = 'POCKET_ACCOUNT_SAVING_WAS_COMPLETED';
    case POCKET_ACCOUNT_REDEEM_WAS_COMPLETED = 'POCKET_ACCOUNT_REDEEM_WAS_COMPLETED';
    case POCKET_ACCOUNT_SAVING_ERROR_OCCURRED = 'POCKET_ACCOUNT_SAVING_ERROR_OCCURRED';
    case POCKET_ACCOUNT_REDEEM_ERROR_OCCURRED = 'POCKET_ACCOUNT_REDEEM_ERROR_OCCURRED';
    case POCKET_ACCOUNT_USER_WAS_CHANGED = 'POCKET_ACCOUNT_USER_WAS_CHANGED';

    // Partner context
    case FEATURE_WAS_ENABLED = 'FEATURE_WAS_ENABLED';
    case FEATURE_WAS_DISABLED = 'FEATURE_WAS_DISABLED';

}
