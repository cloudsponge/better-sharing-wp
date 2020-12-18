import React from 'react';

import Inspector from './SocialNetworksInspector';
import Preview from './SocialNetworksSave';

const { __ } = wp.i18n;
const { Fragment, useEffect } = wp.element;

const SocialNetworksEdit = (props) => {
  const { attributes, setAttributes, component } = props;

  // when a toggle is changed
  const onToggleChange = (key) => {
    let networks = { ...attributes.socialNetworks };
    networks[key].visible = !networks[key].visible;
    setAttributes({ socialNetworks: networks });
  };

  // generates a social sharing intent url
  const generateIntent = (key, val) => {
    let networks = { ...attributes.socialNetworks };

    switch (key) {
      case 'twitter':
        networks[
          key
        ].intentUrl = `https://www.twitter.com/intent/tweet?url=${encodeURIComponent(
          attributes.referralLink
        )}&text=${encodeURIComponent(val)}`;
        setAttributes({ socialNetworks: networks });
        break;
      case 'facebook':
        // currently no custom message, just URL
        networks[
          key
        ].intentUrl = `https://www.facebook.com/sharer/sharer.php?&u=${encodeURIComponent(
          attributes.referralLink
        )}`;
        setAttributes({ socialNetworks: networks });
        break;
      default:
        break;
    }
  };

  // when message input changes
  const onTextChange = (key, val) => {
    let networks = { ...attributes.socialNetworks };
    networks[key].message = val;
    setAttributes({ socialNetworks: networks });
    generateIntent(key, val);
  };

  // first render w/ default attributes
  useEffect(() => {
    let networks = { ...attributes.socialNetworks };
    Object.keys(networks).forEach((key) =>
      generateIntent(key, attributes.socialNetworks[key].message)
    );
  }, []);

  return (
    <Fragment>
      {component === 'inspector' && (
        <Inspector
          {...props}
          onToggleChange={onToggleChange}
          onTextChange={onTextChange}
        />
      )}

      {component === 'preview' && <Preview {...props} />}
    </Fragment>
  );
};

export default SocialNetworksEdit;
