import React from 'react';

import SocialNetworks from './SocialNetworks/SocialNetworksSave';
import ReferralLink from './ReferralLink/ReferralLinkSave';
import EmailInput from './EmailInput/EmailInputSave';
import EmailForm from './EmailForm/EmailFormSave';

const { HorizontalRule } = wp.components;
const { Fragment } = wp.element;

const Save = (props) => {
  const { className } = props;

  return (
    <Fragment>
      <SocialNetworks {...props} />
      <HorizontalRule />
      <ReferralLink {...props} />
      <HorizontalRule />
    </Fragment>
  );
};

export default Save;
