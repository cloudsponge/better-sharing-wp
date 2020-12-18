import React from 'react';

import EmailForm from './EmailForm/EmailFormEdit';
import EmailInput from './EmailInput/EmailInputEdit';
import ReferralLink from './ReferralLink/ReferralLinkEdit';
import SocialNetworks from './SocialNetworks/SocialNetworksEdit';

const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { HorizontalRule } = wp.components;
const { Fragment } = wp.element;

const Edit = (props) => {
  const { className } = props;

  return (
    <Fragment>
      <InspectorControls>
        <SocialNetworks {...props} component={'inspector'} />
        <ReferralLink {...props} component={'inspector'} />
        <EmailForm {...props} component={'inspector'} />
      </InspectorControls>

      {/*** Editor preview ***/}
      <div className={className}>
        <SocialNetworks {...props} component={'preview'} />
        <HorizontalRule />
        <ReferralLink {...props} component={'preview'} />
        <HorizontalRule />
        <EmailInput {...props} component={'preview'} />
        <HorizontalRule />
        <EmailForm {...props} component={'preview'} />
      </div>
    </Fragment>
  );
};

export default Edit;
