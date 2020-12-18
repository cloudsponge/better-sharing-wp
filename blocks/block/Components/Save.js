import React from 'react';

import SocialNetworks from './SocialNetworks/SocialNetworksSave';
import ReferralLink from './ReferralLink/ReferralLinkSave';
import EmailInput from './EmailInput/EmailInputSave';
import EmailForm from './EmailForm/EmailFormSave';

const { HorizontalRule } = wp.components;

const Save = (props) => {
  const { className } = props;

  return (
    <div className={className}>
      <SocialNetworks {...props} />
      <HorizontalRule />
      <ReferralLink {...props} />
      <HorizontalRule />
      <EmailInput {...props} />
      <HorizontalRule />
      <EmailForm {...props} />
    </div>
  );
};

export default Save;
