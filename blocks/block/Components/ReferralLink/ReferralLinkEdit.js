import React from 'react';

import Inspector from './ReferralLinkInspector';
import Preview from './ReferralLinkSave';

const { __ } = wp.i18n;
const { Fragment, useEffect } = wp.element;
const { getPermalink } = wp.data.select('core/editor');

const ReferralLinkEdit = (props) => {
  const { attributes, setAttributes, component } = props;

  const onControlChange = (option) => {
    switch (option) {
      case 'default':
        setAttributes({
          referralLink: getPermalink(),
          referralLinkControl: 'default',
        });
        break;
      case 'custom':
        setAttributes({
          referralLink: 'https://',
          referralLinkControl: 'custom',
        });
        break;
      default:
        break;
    }
  };

  // first render w/ default attribute
  useEffect(() => {
    onControlChange(attributes.referralLink);
  }, []);

  return (
    <Fragment>
      {component === 'inspector' && (
        <Inspector {...props} onControlChange={onControlChange} />
      )}

      {component === 'preview' && <Preview {...props} />}
    </Fragment>
  );
};

export default ReferralLinkEdit;
