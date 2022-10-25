// Dependencies
import { addMultiple, focusFirstDescendant, focusableElements, getExtension, getYouTubeID, getVimeoID, removeMultiple } from './utils.js';

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
    supportedResponsiveImageMimeTypes: ['image/jpeg', 'image/png', 'image/tiff', 'image/webp'],
  };

  const videoMimeTypeMap = {
    '3gp': 'video/3gpp',
    '3gp2': 'video/3gpp2',
    avi: 'video/avi',
    m3u8: 'application/x-mpegURL',
    mkv: 'video/x-matroska',
    mov: 'video/quicktime',
    mp4: 'video/mp4',
    mpeg: 'video/mpeg',
    ogg: 'video/ogg',
    webm: 'video/webm',
    wmv: 'video/x-ms-wmv',
  };

  // Open the lightbox
  const openLightbox = () => {
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].content.length;
    const galleryTitle = lightboxGalleries[lightboxSettings.currentGallery].title;
    let galleryDescription = galleryTotal == 1 ? translations.UNTITLED_DYNAMIC_LABEL_s : translations.UNTITLED_DYNAMIC_LABEL_p;
    if (galleryTitle !== 'untitled') {
      galleryDescription = galleryTotal == 1 ? translations.DYNAMIC_LABEL_s : translations.DYNAMIC_LABEL_p;
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
    clearLightbox();
  };

  // Clear lightbox content
  const clearLightbox = () => {
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
    lightboxContent.classList.remove(cssClasses.lightboxImageLoading);
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
  const renderInfo = (img = null) => {
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].content.length;
    const { title, type } = lightboxGalleries[lightboxSettings.currentGallery].content[lightboxSettings.current];
    const currentContentIndex = lightboxSettings.current + 1;
    // Apply counter settings
    if (lightbox.dataset.showcounter && galleryTotal > 1) {
      lightboxTotal.style.removeProperty('display');
      lightboxTotal.innerHTML = `<span class="${cssClasses.screenReaderOnly} ${cssClasses.screenReaderOnlyClasses}">Image ${currentContentIndex} of ${galleryTotal}.</span><span aria-hidden="true">${currentContentIndex}/${galleryTotal}</span>`;
    }
    // Apply caption settings
    if (lightbox.dataset.showcaptions) {
      lightboxCaption.style.removeProperty('display');
      lightboxCaption.innerHTML = title;
    } else {
      if (img && type == 'image') {
        img.setAttribute('alt', title);
      }
    }
  };

  // Load the previous slide
  const loadPreviousImage = () => {
    lightboxSettings.touch.direction = -1;
    loadLightboxContent(lightboxSettings.current - (isPrevious() ? 1 : 0));
  };

  // Load the next slide
  const loadNextImage = () => {
    lightboxSettings.touch.direction = 1;
    loadLightboxContent(lightboxSettings.current + (isNext() ? 1 : 0));
  };

  // Is there a valid previous slide?
  const isPrevious = () => {
    return lightboxSettings.current > 0;
  };

  // Is there a valid next slide?
  const isNext = () => {
    return lightboxSettings.current < lightboxGalleries[lightboxSettings.currentGallery].content.length - 1;
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

  const lightboxTemplate = (i) => {
    const { type } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    if (type === 'image') {
      return lightboxImageTemplate(i);
    }
    if (type === 'video') {
      return lightboxVideoTemplate(i);
    }
    if (type === 'youtube') {
      return lightboxYouTubeTemplate(i);
    }
    if (type === 'vimeo') {
      return lightboxVimeoTemplate(i);
    }
    if (type === 'query') {
      return lightboxQueryTemplate(i);
    }
  };

  // HTML image template
  const lightboxImageTemplate = (i) => {
    const { mimetype, srcsetImages, srcsetImagesWebp, title, url } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    const isSupported = lightboxSettings.supportedResponsiveImageMimeTypes.includes(mimetype);
    const isResponsive = srcsetImages.length > 0;
    if (isSupported && isResponsive) {
      const defaultImage = srcsetImages[0].indexOf(' ') > 0 ? srcsetImages[0].substring(0, srcsetImages[0].indexOf(' ')) : srcsetImages[0];
      const source = srcsetImages.length > 0 ? `<source type="${mimetype}" srcset="${srcsetImages}" />` : '';
      const sourceWebp = srcsetImagesWebp.length > 0 ? `<source type="image/webp" srcset="${srcsetImagesWebp}" />` : '';
      return `
        <picture class="${cssClasses.lightboxPicture} ${cssClasses.lightboxPictureClasses}">
          ${source}
          ${sourceWebp}
          <img
            alt=""
            class="${cssClasses.lightboxImage} ${cssClasses.lightboxImageClasses}"
            loading="lazy"
            src="${defaultImage}" />
        </picture>
      `;
    }
    // Do we have an image?
    if (mimetype.indexOf('image') < 0) {
      const ext = mimetype.substring(mimetype.lastIndexOf('/') + 1);
      return `<p style="text-align: center;">${translations.UNSUPPORTED_FILETYPE.replace('{ext}', ext)}<br/><a href="${url}" target="_blank">${title}</a><br/>${url.substring(url.lastIndexOf('/') + 1)}</p>`;
    }
    return `
      <img
        alt=""
        class="${cssClasses.lightboxImage} ${cssClasses.lightboxImageClasses}"
        loading="lazy"
        src="${url}" />
    `;
  };

  // HTML Video template
  const lightboxVideoTemplate = (i) => {
    const { mimetype, url } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    const extension = getExtension(url);
    const fileMimeType = mimetype ? mimetype : videoMimeTypeMap[extension];
    if (url && extension && fileMimeType) {
      return `
      <div class="${cssClasses.videoIframeWrapper} ${cssClasses.videoIframeWrapperClasses}">
        <video class="${cssClasses.video} ${cssClasses.videoClasses}" controls playsinline autoplay disablepictureinpicture>
          <source src="${url}" type="${fileMimeType}" />
        </video>
      </div>`;
    } else {
      return `<p>${translations.UNSUPPORTED_VIDEO}</p>`;
    }
  };

  // HTML YouTube template
  const lightboxYouTubeTemplate = (i) => {
    const { title, url } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    const id = getYouTubeID(url);
    if (url && id) {
      return `
      <div class="${cssClasses.videoIframeWrapper} ${cssClasses.videoIframeWrapperClasses}">
        <iframe class="${cssClasses.iframe} ${cssClasses.iframeClasses}" title="${title}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" src="https://www.youtube-nocookie.com/embed/${id}?autoplay=1&amp;playsinline=1"></iframe>
      </div>
    `;
    } else {
      return `<p>${translations.UNSUPPORTED_YOUTUBE}</p>`;
    }
  };

  // HTML Vimeo template
  const lightboxVimeoTemplate = (i) => {
    const { title, url } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    const id = getVimeoID(url);
    if (url && id) {
      return `<div class="${cssClasses.videoIframeWrapper} ${cssClasses.videoIframeWrapperClasses}">
      <iframe class="${cssClasses.iframe} ${cssClasses.iframeClasses}" title="${title}" allow="autoplay; fullscreen; picture-in-picture; encrypted-media; accelerometer; gyroscope" src="https://player.vimeo.com/video/${id}?loop=false&amp;autoplay=true&amp;muted=false&amp;gesture=media&amp;playsinline=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=false&amp;customControls=true"></iframe>
    </div>
    `;
    } else {
      return `<p>${translations.UNSUPPORTED_VIMEO}</p>`;
    }
  };

  // HTML template from query
  const lightboxQueryTemplate = (i) => {
    const { target } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
    const element = document.querySelector(target);
    if (element) {
      return element.innerHTML;
    } else {
      return `<p>${translations.UNSUPPORTED_QUERY}</p>`;
    }
  };

  // Load content from the lightbox content array
  const loadLightboxContent = (i) => {
    // Check this is a valid content to load
    const galleryTotal = lightboxGalleries[lightboxSettings.currentGallery].content.length;
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
          resetContentPosition();
          const newThumbnail = lightboxTemplate(i);
          const { averageColor, type } = lightboxGalleries[lightboxSettings.currentGallery].content[i];
          lightboxContent.innerHTML = newThumbnail;
          if (averageColor) {
            const hslColor = averageColor.replace(/[^\d,]/g, '').split(',');
            lightbox.style.backgroundColor = `hsla(${hslColor[0]},${hslColor[1]}%,${hslColor[2] * 0.5}%,0.95)`;
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
  const resetContentPosition = () => {
    setPosition(0);
    clearTransitions();
  };

  // Set position to a given x value
  const setPosition = (x) => {
    lightboxContent.style.transform = `translate3d(${x}, 0, 0)`;
  };

  // Set end position for transition
  const addEndTransition = () => {
    const endPosition = lightboxSettings.touch.direction > 0 ? '-200%' : lightboxSettings.touch.direction < 0 ? '200%' : 0;
    lightboxContent.style.transform = `translate3d(${endPosition}, 0, 0)`;
    lightboxContent.style.transitionDuration = `${lightboxSettings.timing}ms`;
    lightboxContent.style.transitionProperty = `transform`;
  };

  // Clear CSS transitions
  const clearTransitions = () => {
    lightboxContent.style.transitionDuration = `0ms`;
    lightboxContent.style.transform = `transform: translate3d(0, 0, 0)`;
  };

  // Parse lightbox content
  if (lightboxLinks.length > 0) {
    lightboxLinks.forEach((lightboxLink) => {
      const galleryRef = lightboxLink.dataset.gallery ? lightboxLink.dataset.gallery : 'default';

      // Set default settings for this gallery
      if (typeof lightboxGalleries[galleryRef] === 'undefined') {
        const galleryContainer = document.getElementById(galleryRef);
        const galleryTitle = galleryContainer && galleryContainer.dataset.title ? galleryContainer.dataset.title : 'untitled';
        lightboxGalleries[galleryRef] = {
          content: [],
          ref: galleryRef,
          title: galleryTitle,
        };
      }

      // Dataset attrs
      const { averagecolor = null, mimetype, orientation, ref = null, srcset = '', srcsetwebp = '', target, title, type, url } = lightboxLink.dataset;

      // Add to lightbox content array for this gallery
      lightboxGalleries[galleryRef].content.push({
        averageColor: averagecolor,
        gallery: galleryRef,
        mimetype,
        orientation,
        ref,
        srcsetImages: srcset.indexOf(',') > 0 ? srcset.split(',') : [],
        srcsetImagesWebp: srcsetwebp.indexOf(',') > 0 ? srcsetwebp.split(',') : [],
        target,
        title,
        type,
        url,
      });

      const currentIndex = lightboxGalleries[galleryRef].content.length - 1;

      // Add click event to the anchor
      lightboxLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (!lightboxSettings.open) {
          lightboxSettings.currentGallery = galleryRef;
          loadLightboxContent(currentIndex);
          window.initialFocus = e.currentTarget;
        }
      });
    });

    // Keyboard controls
    window.addEventListener('keydown', (e) => {
      if (lightboxSettings.open) {
        const lightboxFocusableElements = focusableElements(lightbox);
        const firstFocusableElement = lightboxFocusableElements[0];
        const altFirstFocusableElement = lightboxFocusableElements[1];
        const lastFocusableElement = lightboxFocusableElements[lightboxFocusableElements.length - 1];
        const altLastFocusableElement = lightboxFocusableElements[lightboxFocusableElements.length - 2];

        const handleBackwardTab = () => {
          if (
            document.activeElement === firstFocusableElement ||
            (firstFocusableElement.disabled && document.activeElement === altFirstFocusableElement) ||
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
        if (lightboxSettings.touch.lastPos - lightboxSettings.touch.startPos > lightboxSettings.touch.moveThreshold) {
          if (isPrevious()) {
            loadPreviousImage();
          } else {
            setPosition(0);
          }
        }
        if (lightboxSettings.touch.lastPos - lightboxSettings.touch.startPos < -lightboxSettings.touch.moveThreshold) {
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
