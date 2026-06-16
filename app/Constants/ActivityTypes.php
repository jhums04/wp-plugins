<?php

namespace AgencyCRM\Constants;

class ActivityTypes
{
    public const LEAD_CREATED   = 'lead_created';
    public const LEAD_UPDATED   = 'lead_updated';
    public const STAGE_CHANGED  = 'stage_changed';

    // future-proof
    public const NOTE_ADDED     = 'note_added';
    public const CALL_LOGGED    = 'call_logged';
    public const EMAIL_SENT     = 'email_sent';
}