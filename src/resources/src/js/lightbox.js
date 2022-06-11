// Dependencies
import { addMultiple, focusFirstDescendant, focusableElements, removeMultiple } from './utils.js';

// Lightbox functionality
const initLightbox = ({ cssClasses, identifier, launchLightboxCssClass, translations }) => {
  const body = document.querySelector('body');
  const lightbox = document.getElementById(`${identifier}-modal`);
  const lightboxWrapper = document.getElementById(`${identifier}-container`);
  const lightboxContent = document.getElementById(`${identifier}-frame`);

  const lightboxPrevious = document.getElementById(`${identifier}-control-previous`);
  const lightboxNext = document.getElementById(`${identifier}-control-next`);
  const lightboxClose = document.getElementById(`${identifier}-control-close`);
  const lightboxCaption = document.getElementById(`${identifier}-info-caption`);
  const lightboxTotal = document.getElementById(`${identifier}-info-total`);

  const lightboxLinks = document.querySelectorAll(`.${launchLightboxCssClass}`);
  const lightboxGalleries = {};

  const lightboxSettings = {
    timing: 250,
    currentGallery: 'default',
    current: -1,
    open: false,
    touch: {
      startPos: 0,
      lastPos: 0,
      direction: 0,
      moveThreshold: 50,
    },
    lastKeyboardControl: false,
    isTouchDevice: 'ontouchstart' in window,
    supportedResponsiveMimeTypes: ['image/jpeg', 'image/png', 'image/tiff', 'image/webp'],
  };

  // Open the lightbox
  const openLightbox = () => {
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].images.length;
    const galleryTitle = lightboxGalleries[lightboxSettings.currentGallery].title;
    let galleryDescription =
      galleryTotal == 1
        ? translations.UNTITLED_DYNAMIC_LABEL_s
        : translations.UNTITLED_DYNAMIC_LABEL_p;
    if (galleryTitle !== 'untitled') {
      galleryDescription =
        galleryTotal == 1 ? translations.DYNAMIC_LABEL_s : translations.DYNAMIC_LABEL_p;
    }
    galleryDescription = galleryDescription.replace('{total}', galleryTotal);
    galleryDescription = galleryDescription.replace('{title}', galleryTitle);
    // Update aria label
    lightbox.setAttribute('aria-label', galleryDescription);
    lightbox.style.display = 'flex';
    lightbox.setAttribute('aria-hidden', false);
    body.classList.add(cssClasses.disableScroll);
    addMultiple(body, cssClasses.disableScrollClasses);
    lightboxSettings.open = true;
    lightboxWrapper.focus();
    focusFirstDescendant(lightboxWrapper, lightboxClose);
  };

  // Close the lightbox and reset all settings
  const closeLightbox = () => {
    if (window.initialFocus) {
      window.initialFocus.focus();
    }
    lightbox.style.display = 'none';
    lightbox.setAttribute('aria-label', translations.LABEL);
    lightbox.setAttribute('aria-hidden', true);
    body.classList.remove(cssClasses.disableScroll);
    removeMultiple(body, cssClasses.disableScrollClasses);
    lightboxSettings.current = -1;
    lightboxSettings.open = false;
    lightboxTotal.style.display = 'none';
    lightboxTotal.innerText = ``;
    lightboxCaption.style.display = 'none';
    lightboxContent.innerHTML = ``;
    lightboxContent.classList.remove(`${cssClasses.lightboxContent}--loading`);
    lightboxSettings.lastKeyboardControl = false;
  };

  // Render the navigational controls
  const renderControls = () => {
    // Show appropriate controls
    if (!isPrevious() && !isNext()) {
      disableNavControls();
    } else {
      enableNavControls();
      // Show previous navigation control if there is a previous image
      if (isPrevious()) {
        lightboxPrevious.classList.remove('visibility-hidden');
        lightboxPrevious.disabled = false;
      } else {
        lightboxPrevious.classList.add('visibility-hidden');
        lightboxPrevious.disabled = true;
      }
      // Show previous navigation control if there is a next image
      if (isNext()) {
        lightboxNext.classList.remove('visibility-hidden');
        lightboxNext.disabled = false;
      } else {
        lightboxNext.classList.add('visibility-hidden');
        lightboxNext.disabled = true;
      }
    }
    if (lightboxSettings.lastKeyboardControl === 'right') {
      if (!lightboxNext.disabled) {
        lightboxNext.focus();
      } else {
        lightbox.focus();
      }
    }
    if (lightboxSettings.lastKeyboardControl === 'left') {
      if (!lightboxPrevious.disabled) {
        lightboxPrevious.focus();
      } else {
        lightbox.focus();
      }
    }
  };

  // Render the navigational controls
  const renderInfo = (img) => {
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].images.length;
    const { title } =
      lightboxGalleries[lightboxSettings.currentGallery].images[lightboxSettings.current];
    const currentImageIndex = lightboxSettings.current + 1;
    // Apply counter settings
    if (lightbox.dataset.showcounter) {
      lightboxTotal.style.removeProperty('display');
      lightboxTotal.innerHTML = `<span class="${cssClasses.screenReaderOnly} ${cssClasses.screenReaderOnlyClasses}">Image ${currentImageIndex} of ${galleryTotal}.</span><span aria-hidden="true">${currentImageIndex}/${galleryTotal}</span>`;
    }
    // Apply caption settings
    if (lightbox.dataset.showcaptions) {
      lightboxCaption.style.removeProperty('display');
      lightboxCaption.innerHTML = title;
    } else {
      img.setAttribute('alt', title);
    }
  };

  // Load the previous image
  const loadPreviousImage = () => {
    lightboxSettings.touch.direction = -1;
    loadLightboxImage(lightboxSettings.current - (isPrevious() ? 1 : 0));
  };

  // Load the next image
  const loadNextImage = () => {
    lightboxSettings.touch.direction = 1;
    loadLightboxImage(lightboxSettings.current + (isNext() ? 1 : 0));
  };

  // Is there a valid previous image?
  const isPrevious = () => {
    return lightboxSettings.current > 0;
  };

  // Is there a valid next image?
  const isNext = () => {
    return (
      lightboxSettings.current <
      lightboxGalleries[lightboxSettings.currentGallery].images.length - 1
    );
  };

  // Disable navigation controls
  const disableNavControls = () => {
    lightboxPrevious.style.setProperty('display', 'none');
    lightboxNext.style.setProperty('display', 'none');
  };

  // Enable navigation controls
  const enableNavControls = () => {
    lightboxPrevious.style.removeProperty('display');
    lightboxNext.style.removeProperty('display');
  };

  // HTML template for a lightbox image
  const lightboxImageTemplate = (i) => {
    const { mimetype, srcsetImages, srcsetImagesWebp, title, url } =
      lightboxGalleries[lightboxSettings.currentGallery].images[i];
    const isSupported = lightboxSettings.supportedResponsiveMimeTypes.includes(mimetype);
    const isResponsive = srcsetImages.length > 0;
    if (isSupported && isResponsive) {
      const source =
        srcsetImages.length > 0
          ? `
      <source
        type="${mimetype}"
        srcset="${srcsetImages.map((image) => {
          return `${image},`;
        })}" />`
          : '';
      const sourceWebp =
        srcsetImagesWebp.length > 0
          ? `
      <source
        type="image/webp"
        srcset="${srcsetImagesWebp.map((image) => {
          return `${image},`;
        })}" />`
          : '';
      return `
        <picture class="${cssClasses.lightboxPicture}">
          ${source}
          ${sourceWebp}
          <img
            alt=""
            class="${cssClasses.lightboxImage}"
            loading="lazy"
            src="${srcsetImages[0]}" />
        </picture>
      `;
    }
    // Do we have an image?
    if (mimetype.indexOf('image') < 0) {
      const ext = mimetype.substring(mimetype.lastIndexOf('/') + 1);
      return `<p style="text-align: center;">${translations.UNSUPPORTED_FILETYPE.replace(
        '{ext}',
        ext
      )}<br/><a href="${url}" target="_blank">${title}</a><br/>${url.substring(
        url.lastIndexOf('/') + 1
      )}</p>`;
    }
    return `
      <img
        alt=""
        class="${cssClasses.lightboxImage}"
        loading="lazy"
        src="${url}" />
    `;
  };

  // Load an image from the lightbox images array
  const loadLightboxImage = (i) => {
    // Check this is a valid image to load
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].images.length;
    const isValid = i >= 0 && i <= galleryTotal - 1 && galleryTotal > 0;
    if (isValid) {
      if (!lightboxSettings.open) {
        openLightbox();
      }
      if (lightboxSettings.current !== i) {
        lightboxSettings.current = i;
        renderControls();
        addEndTransition();
        window.setTimeout(() => {
          resetImagePosition();
          const newThumbnail = lightboxImageTemplate(i);
          const { averageColor } = lightboxGalleries[lightboxSettings.currentGallery].images[i];
          lightboxContent.innerHTML = newThumbnail;
          if (averageColor) {
            const hslColor = averageColor.replace(/[^\d,]/g, '').split(',');
            lightbox.style.backgroundColor = `hsla(${hslColor[0]},${hslColor[1]}%,${
              hslColor[2] * 0.5
            }%,0.95)`;
          }
          const img = lightboxContent.querySelector('img');
          if (img) {
            lightboxContent.classList.add(cssClasses.lightboxImageLoading);
            img.onload = () => {
              lightboxContent.classList.remove(cssClasses.lightboxImageLoading);
            };
          }
          renderInfo(img);
        }, lightboxSettings.timing);
      }
    }
  };

  // Reset image position and transitions
  const resetImagePosition = () => {
    setPosition(0);
    clearTransitions();
  };

  // Set position to a given x value
  const setPosition = (x) => {
    lightboxContent.style.transform = `translate3d(${x}, 0, 0)`;
  };

  // Set end position for transition
  const addEndTransition = () => {
    const endPosition =
      lightboxSettings.touch.direction > 0
        ? '-200%'
        : lightboxSettings.touch.direction < 0
        ? '200%'
        : 0;
    lightboxContent.style.transform = `translate3d(${endPosition}, 0, 0)`;
    lightboxContent.style.transitionDuration = `${lightboxSettings.timing}ms`;
    lightboxContent.style.transitionProperty = `transform`;
  };

  // Clear CSS transitions
  const clearTransitions = () => {
    lightboxContent.style.transitionDuration = `0ms`;
    lightboxContent.style.transform = `transform: translate3d(0, 0, 0)`;
  };

  // Parse lightbox images
  if (lightboxLinks.length > 0) {
    lightboxLinks.forEach((lightboxLink) => {
      const galleryRef = lightboxLink.dataset.gallery ? lightboxLink.dataset.gallery : 'default';

      // Set default settings for this gallery
      if (typeof lightboxGalleries[galleryRef] === 'undefined') {
        const galleryContainer = document.getElementById(galleryRef);
        const galleryTitle =
          galleryContainer && galleryContainer.dataset.title
            ? galleryContainer.dataset.title
            : 'untitled';
        lightboxGalleries[galleryRef] = {
          ref: galleryRef,
          title: galleryTitle,
          images: [],
        };
      }

      // Dataset attrs
      const {
        averagecolor = null,
        mimetype,
        orientation,
        ref = null,
        srcset = '',
        srcsetwebp = '',
        title,
        url,
      } = lightboxLink.dataset;

      // Add to lightbox images array for this gallery
      lightboxGalleries[galleryRef].images.push({
        url,
        title,
        orientation,
        srcsetImages: srcset.split(','),
        srcsetImagesWebp: srcsetwebp.split(','),
        mimetype,
        ref,
        gallery: galleryRef,
        averageColor: averagecolor,
      });

      const currentIndex = lightboxGalleries[galleryRef].images.length - 1;

      // Add click event to the anchor
      lightboxLink.addEventListener('click', (e) => {
        e.preventDefault();
        lightboxSettings.currentGallery = galleryRef;
        loadLightboxImage(currentIndex);
        window.initialFocus = e.currentTarget;
      });
    });

    // Keyboard controls
    window.addEventListener('keydown', (e) => {
      if (lightboxSettings.open) {
        const lightboxFocusableElements = focusableElements(lightbox);
        const firstFocusableElement = lightboxFocusableElements[0];
        const altFirstFocusableElement = lightboxFocusableElements[1];
        const lastFocusableElement =
          lightboxFocusableElements[lightboxFocusableElements.length - 1];
        const altLastFocusableElement =
          lightboxFocusableElements[lightboxFocusableElements.length - 2];

        const handleBackwardTab = () => {
          if (
            document.activeElement === firstFocusableElement ||
            (firstFocusableElement.disabled &&
              document.activeElement === altFirstFocusableElement) ||
            (firstFocusableElement.disabled && document.activeElement === lightbox)
          ) {
            e.preventDefault();
            if (!lastFocusableElement.disabled) {
              lastFocusableElement.focus();
            } else {
              altLastFocusableElement.focus();
            }
          }
        };

        const handleForwardTab = () => {
          if (
            document.activeElement === lastFocusableElement ||
            (lastFocusableElement.disabled && document.activeElement === altLastFocusableElement) ||
            (lastFocusableElement.disabled && document.activeElement === lightbox)
          ) {
            e.preventDefault();
            if (!firstFocusableElement.disabled) {
              firstFocusableElement.focus();
            } else {
              altFirstFocusableElement.focus();
            }
          }
          if (firstFocusableElement.disabled && lastFocusableElement.disabled) {
            e.preventDefault();
            lightboxClose.focus();
          }
        };

        // Trap tab within modal
        if (e.key === 'Tab') {
          if (lightboxFocusableElements.length === 1) {
            e.preventDefault();
          }

          if (e.shiftKey) {
            handleBackwardTab();
          } else {
            handleForwardTab();
          }
        }

        if (e.keyCode === 32 || e.key === 'Enter') {
          if (document.activeElement === lightboxPrevious) {
            lightboxSettings.lastKeyboardControl = 'ArrowLeft';
          }
          if (document.activeElement === lightboxNext) {
            lightboxSettings.lastKeyboardControl = 'ArrowRight';
          }
        }

        if (e.key === 'Escape') {
          closeLightbox();
        }
        if (e.key === 'ArrowLeft') {
          lightboxSettings.lastKeyboardControl = 'ArrowLeft';
          loadPreviousImage();
        }
        if (e.key === 'ArrowRight') {
          lightboxSettings.lastKeyboardControl = 'ArrowRight';
          loadNextImage();
        }
      }
    });

    // Click outside the lightbox content to close
    lightbox.addEventListener('click', (e) => {
      if (e.target.id === `${identifier}-modal` || e.target.id === `${identifier}-frame`) {
        e.preventDefault();
        closeLightbox();
      }
    });

    // Previous button
    lightboxPrevious.addEventListener('click', (e) => {
      e.preventDefault();
      loadPreviousImage();
    });

    // Next button
    lightboxNext.addEventListener('click', (e) => {
      e.preventDefault();
      loadNextImage();
    });

    // Close button
    lightboxClose.addEventListener('click', (e) => {
      e.preventDefault();
      closeLightbox();
    });

    // Touch movement event
    lightboxContent.addEventListener(
      'touchmove',
      (e) => {
        if (lightboxSettings.touch.lastPos > lightboxSettings.touch.startPos) {
          lightboxSettings.touch.direction = -1;
        } else {
          lightboxSettings.touch.direction = 1;
        }

        // If we're moving the content to the right, and we're not on the first slide...
        if (lightboxSettings.touch.direction < 0 && !isNext()) {
          setPosition(e.touches[0].clientX - lightboxSettings.touch.startPos);
        }

        // If we're moving the content to the left, and we're not on the last slide...
        if (lightboxSettings.touch.direction > 0 && !isPrevious()) {
          setPosition(e.touches[0].clientX - lightboxSettings.touch.startPos);
        }

        // If we've got a netx/previous, allow move
        if (isPrevious() && isNext()) {
          setPosition(e.touches[0].clientX - lightboxSettings.touch.startPos);
        }

        lightboxSettings.touch.lastPos = e.touches[0].clientX;
      },
      { passive: true }
    );

    // Touch start event
    lightboxContent.addEventListener(
      'touchstart',
      (e) => {
        lightboxSettings.touch.startPos = e.touches[0].clientX;
        lightboxSettings.touch.direction = 0;
        clearTransitions();
      },
      { passive: true }
    );

    // Touch end event
    lightboxContent.addEventListener(
      'touchend',
      () => {
        if (
          lightboxSettings.touch.lastPos - lightboxSettings.touch.startPos >
          lightboxSettings.touch.moveThreshold
        ) {
          if (isPrevious()) {
            loadPreviousImage();
          } else {
            setPosition(0);
          }
        }
        if (
          lightboxSettings.touch.lastPos - lightboxSettings.touch.startPos <
          -lightboxSettings.touch.moveThreshold
        ) {
          if (isNext()) {
            loadNextImage();
          } else {
            setPosition(0);
          }
        }
      },
      { passive: true }
    );
  }
};

window.initLightbox = initLightbox;
