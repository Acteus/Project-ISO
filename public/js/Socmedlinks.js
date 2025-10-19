// Social Media Links for Login Page
// Author: GitHub Copilot
// Description: Creates dynamic social media links with JavaScript

// Social Media Data Configuration
const socialMediaData = [
  {
    name: 'Gmail',
    url: 'mailto:info@jru.edu.ph',
    icon: 'fas fa-envelope',
    iconType: 'fontawesome',
    className: 'gmail',
    target: '_self',
    color: 'rgba(234, 67, 53, 0.8)' // Gmail red
  },
  {
    name: 'Facebook',
    url: 'https://facebook.com/joserizaluniversity',
    icon: 'fab fa-facebook-f',
    iconType: 'fontawesome',
    className: 'facebook',
    target: '_blank',
    color: 'rgba(24, 119, 242, 0.8)' // Facebook blue
  },
  {
    name: 'Instagram',
    url: 'https://www.instagram.com/jru1919/',
    icon: 'fab fa-instagram',
    iconType: 'fontawesome',
    className: 'instagram',
    target: '_blank',
    color: 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)' // Instagram gradient
  },
  {
    name: 'TikTok',
    url: 'https://www.tiktok.com/@jru1919?lang=en',
    icon: 'fab fa-tiktok',
    iconType: 'fontawesome',
    className: 'tiktok',
    target: '_blank',
    color: 'rgba(0, 0, 0, 0.8)' // TikTok black
  }
];

// Function to add Font Awesome CSS
function addFontAwesome() {
  // Add Font Awesome CSS if not already present
  if (!document.querySelector('link[href*="font-awesome"]') && !document.querySelector('link[href*="fontawesome"]')) {
    const fontAwesome = document.createElement('link');
    fontAwesome.rel = 'stylesheet';
    fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
    fontAwesome.crossOrigin = 'anonymous';
    document.head.appendChild(fontAwesome);
    console.log('Font Awesome loaded');
  }
}

// Function to create social media CSS styles
function createSocialMediaStyles() {
  const style = document.createElement('style');
  style.textContent = `
    /* Social Media Container */
    .social-media-container {
      position: fixed;
      bottom: 20px;
      left: 20px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      z-index: 1000;
    }

    /* Individual Social Media Links */
    .social-media-link {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      text-decoration: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* Hover Effects */
    .social-media-link:hover {
      transform: translateY(-3px) scale(1.1);
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Social Media Icons */
    .social-icon {
      font-size: 20px;
      transition: transform 0.2s ease;
      color: white;
    }

    .social-media-link:hover .social-icon {
      transform: scale(1.2);
    }

    /* Font Awesome specific styling */
    .social-icon.fas,
    .social-icon.fab {
      font-weight: 900;
    }

    /* Platform-specific hover colors */
    .social-media-link.gmail:hover {
      background: rgba(234, 67, 53, 0.8) !important;
      border-color: rgba(234, 67, 53, 1);
    }

    .social-media-link.facebook:hover {
      background: rgba(24, 119, 242, 0.8) !important;
      border-color: rgba(24, 119, 242, 1);
    }

    .social-media-link.instagram:hover {
      background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%) !important;
      border-color: #e1306c;
    }

    .social-media-link.tiktok:hover {
      background: rgba(0, 0, 0, 0.8) !important;
      border-color: rgba(0, 0, 0, 1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .social-media-container {
        bottom: 15px;
        left: 15px;
        gap: 10px;
      }

      .social-media-link {
        width: 45px;
        height: 45px;
      }

      .social-icon {
        font-size: 20px;
      }
    }
  `;
  
  document.head.appendChild(style);
}

// Function to create social media links
function createSocialMediaLinks() {
  // Create container
  const container = document.createElement('div');
  container.className = 'social-media-container';
  container.id = 'social-media-container';
  
  // Create links for each platform
  socialMediaData.forEach((platform, index) => {
    // Create link element
    const link = document.createElement('a');
    link.href = platform.url;
    link.target = platform.target;
    link.className = `social-media-link ${platform.className}`;
    link.title = `Visit our ${platform.name}`;
    link.setAttribute('aria-label', platform.name);
    
    // Create icon element
    const icon = document.createElement('i');
    if (platform.iconType === 'fontawesome') {
      icon.className = `social-icon ${platform.icon}`;
    } else {
      // Fallback for emoji or text icons
      icon.className = 'social-icon';
      icon.textContent = platform.icon;
    }
    
    // Add click animation
    link.addEventListener('click', function(e) {
      // Add click animation
      this.style.transform = 'translateY(-3px) scale(0.95)';
      
      // Reset animation after a short delay
      setTimeout(() => {
        this.style.transform = '';
      }, 150);
      
      // Log click for analytics (optional)
      console.log(`Social media click: ${platform.name}`);
    });
    
    // Add staggered entrance animation
    link.style.animationDelay = `${index * 100}ms`;
    link.style.opacity = '0';
    link.style.transform = 'translateX(-20px)';
    
    // Animate in after a short delay
    setTimeout(() => {
      link.style.transition = 'all 0.5s ease';
      link.style.opacity = '1';
      link.style.transform = 'translateX(0)';
    }, index * 100 + 100);
    
    // Append icon to link
    link.appendChild(icon);
    
    // Append link to container
    container.appendChild(link);
  });
  
  // Append container to body
  document.body.appendChild(container);
  
  console.log('Social media links created successfully!');
}

// Function to remove social media links (if needed)
function removeSocialMediaLinks() {
  const container = document.getElementById('social-media-container');
  if (container) {
    container.remove();
    console.log('Social media links removed.');
  }
}

// Function to update a social media URL
function updateSocialMediaUrl(platformName, newUrl) {
  const platform = socialMediaData.find(p => p.name.toLowerCase() === platformName.toLowerCase());
  if (platform) {
    platform.url = newUrl;
    // Recreate the links with updated URL
    removeSocialMediaLinks();
    createSocialMediaLinks();
    console.log(`Updated ${platformName} URL to: ${newUrl}`);
  } else {
    console.error(`Platform ${platformName} not found`);
  }
}

// Function to add a new social media platform
function addSocialMediaPlatform(platformData) {
  // Validate required fields
  if (!platformData.name || !platformData.url || !platformData.icon) {
    console.error('Platform data must include name, url, and icon');
    return;
  }
  
  // Set default values
  const newPlatform = {
    name: platformData.name,
    url: platformData.url,
    icon: platformData.icon,
    className: platformData.className || platformData.name.toLowerCase(),
    target: platformData.target || '_blank',
    color: platformData.color || 'rgba(128, 128, 128, 0.8)'
  };
  
  socialMediaData.push(newPlatform);
  
  // Recreate links
  removeSocialMediaLinks();
  createSocialMediaLinks();
  
  console.log(`Added new platform: ${newPlatform.name}`);
}

// Initialize social media functionality
function initializeSocialMedia() {
  // Add Font Awesome
  addFontAwesome();
  
  // Create CSS styles
  createSocialMediaStyles();
  
  // Create social media links (with slight delay to ensure Font Awesome loads)
  setTimeout(() => {
    createSocialMediaLinks();
  }, 100);
  
  // Log initialization
  console.log('Social Media System initialized');
  console.log('Available platforms:', socialMediaData.map(p => p.name));
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded, initializing social media links...');
  initializeSocialMedia();
});

// Export functions for external use (if needed)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    initializeSocialMedia,
    createSocialMediaLinks,
    removeSocialMediaLinks,
    updateSocialMediaUrl,
    addSocialMediaPlatform,
    socialMediaData
  };
}

// Make functions available globally
window.SocialMediaManager = {
  init: initializeSocialMedia,
  create: createSocialMediaLinks,
  remove: removeSocialMediaLinks,
  updateUrl: updateSocialMediaUrl,
  addPlatform: addSocialMediaPlatform,
  getData: () => socialMediaData
};
