import React from 'react';

const { __ } = wp.i18n;
const { Button } = wp.components;

const ReferralLinkSave = ({ attributes }) => {
  return (
    <div className='referral-link'>
      <label htmlFor='referral-link'>Your referral link</label>
      <input
        type='text'
        id='referral-link'
        value={attributes.referralLink}
        readOnly={attributes.referralLinkControl !== 'custom' ? true : false}
      />
      <Button isSecondary isSmall icon='admin-page' id='referral-btn-copy'>
        Copy Link
      </Button>
      <p id="copy-success">Copied!</p>
    </div>
  );
};

export default ReferralLinkSave;
