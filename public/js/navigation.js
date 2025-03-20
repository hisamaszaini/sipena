// DOM elements
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const footer = document.getElementById('footer');
const toggleSidebarBtn = document.getElementById('toggleSidebar');
const hamburgerWrapper = document.getElementById('hamburgerWrapper');
const profileMenuButton = document.getElementById('profileMenuButton');
const profileMenu = document.getElementById('profileMenu');
const profileMenuContainer = document.getElementById('profileMenuContainer');

// Set default sidebar state based on screen size and localStorage
function initializeSidebarState() {
  const isDesktop = window.innerWidth >= 768;
  const storedSidebarState = localStorage.getItem('sidebarCollapsed');
  const isSidebarCollapsed = storedSidebarState === 'true';
  
  // Temporarily disable transitions during page load to prevent initial animation
  sidebar.style.transition = 'none';
  mainContent.style.transition = 'none';
  hamburgerWrapper.style.transition = 'none';
  footer.style.transition = 'none';
  profileMenuContainer.style.transition = 'none';
  
  // Apply saved state or default (expanded for desktop, collapsed for mobile)
  if (storedSidebarState !== null) {
    // Use stored preference if available
    if (isSidebarCollapsed) {
      collapseSidebar();
    } else {
      expandSidebar();
    }
  } else {
    // Default state if no stored preference
    if (isDesktop) {
      expandSidebar();
    } else {
      collapseSidebar();
    }
    // Save default state
    localStorage.setItem('sidebarCollapsed', !isDesktop);
  }
  
  // Re-enable transitions after a small delay
  setTimeout(() => {
    sidebar.style.transition = '';
    mainContent.style.transition = '';
    hamburgerWrapper.style.transition = '';
    footer.style.transition = '';
    profileMenuContainer.style.transition = '';
  }, 50);
}

// Function to collapse sidebar
function collapseSidebar() {
  sidebar.classList.remove('expanded');
  sidebar.classList.add('collapsed');
  mainContent.classList.remove('sidebar-expanded');
  mainContent.classList.add('sidebar-collapsed');
  hamburgerWrapper.classList.remove('sidebar-expanded');
  hamburgerWrapper.classList.add('sidebar-collapsed');
  profileMenuContainer.classList.remove('sidebar-expanded');
  profileMenuContainer.classList.add('sidebar-collapsed');
  footer.style.marginLeft = '0';
}

// Function to expand sidebar
function expandSidebar() {
  sidebar.classList.remove('collapsed');
  sidebar.classList.add('expanded');
  mainContent.classList.remove('sidebar-collapsed');
  mainContent.classList.add('sidebar-expanded');
  hamburgerWrapper.classList.remove('sidebar-collapsed');
  hamburgerWrapper.classList.add('sidebar-expanded');
  profileMenuContainer.classList.remove('sidebar-collapsed');
  profileMenuContainer.classList.add('sidebar-expanded');
  if (window.innerWidth > 768){ 
    footer.style.marginLeft = '16rem';
  }
}

function toggleSidebar() {
  const isSidebarExpanded = sidebar.classList.contains('expanded');
  
  // Toggle sidebar state regardless of device
  if (isSidebarExpanded) {
    collapseSidebar();
  } else {
    expandSidebar();
    // Close profile menu when sidebar is expanded
    profileMenu.classList.remove('active');
  }

  // Save status to localStorage
  localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Toggle Profile Menu
profileMenuButton.addEventListener('click', function(e) {
  e.stopPropagation();
  profileMenu.classList.toggle('active');
});

// Close profile menu when clicking outside
document.addEventListener('click', function(event) {
  if (!profileMenuContainer.contains(event.target)) {
    profileMenu.classList.remove('active');
  }
});

// Toggle sidebar when the hamburger button is clicked
toggleSidebarBtn.addEventListener('click', toggleSidebar);

// Handle window resize events to adjust layout
window.addEventListener('resize', function() {
  const isDesktop = window.innerWidth >= 768;
  
  // Adjust footer margin based on current sidebar state and screen size
  if (isDesktop && sidebar.classList.contains('expanded')) {
    footer.style.marginLeft = '16rem';
  } else {
    footer.style.marginLeft = '0';
  }
  
  // Hide profile menu on resize
  profileMenu.classList.remove('active');
});

// Close the profile menu when ESC key is pressed
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    profileMenu.classList.remove('active');

    // Also collapse sidebar on ESC when on mobile
    if (window.innerWidth < 768 && sidebar.classList.contains('expanded')) {
      toggleSidebar();
    }
  }
});

// Handle page navigation for mobile
function addNavigationListeners() {
  // Get all navigation links that lead to different pages
  const navLinks = document.querySelectorAll('#sidebar a[href]');
  
  navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      const isDesktop = window.innerWidth >= 768;
      
      // For mobile: collapse sidebar when clicking on a navigation link
      if (!isDesktop) {
        // Disable transition temporarily to avoid animation on navigation
        sidebar.style.transition = 'none';
        mainContent.style.transition = 'none';
        hamburgerWrapper.style.transition = 'none';
        footer.style.transition = 'none';
        profileMenuContainer.style.transition = 'none';
        
        collapseSidebar();
        localStorage.setItem('sidebarCollapsed', 'true');
      }
      
      // Continue with regular navigation
    });
  });
}

// Add a lightweight solution to detect page loads from browser navigation
// This will run on both initial load and on navigation
document.addEventListener('DOMContentLoaded', function() {
  // Temporarily disable transitions during page load to prevent animation
  sidebar.style.transition = 'none';
  mainContent.style.transition = 'none';
  hamburgerWrapper.style.transition = 'none';
  footer.style.transition = 'none';
  profileMenuContainer.style.transition = 'none';
  
  initializeSidebarState();
  addNavigationListeners();
  
  // Re-enable transitions after a small delay
  setTimeout(() => {
    sidebar.style.transition = '';
    mainContent.style.transition = '';
    hamburgerWrapper.style.transition = '';
    footer.style.transition = '';
    profileMenuContainer.style.transition = '';
  }, 50);
});

// Call initialization immediately in case script runs after DOM is already loaded
initializeSidebarState();

// Active Navigation
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll("a[href]");
  const currentPath = window.location.pathname.replace(/^\/|\/$/g, "");

  console.log(currentPath);

  links.forEach(link => {
      const linkPath = link.getAttribute("href").replace(/^\/|\/$/g, "").split("/").pop();

      if (linkPath === currentPath) {
          link.classList.add("bg-gray-700");

          const dropdownContent = link.closest('.dropdown-content');
          if (dropdownContent) {
              const dropdownToggle = dropdownContent.previousElementSibling;
              if (dropdownToggle) {
                  dropdownToggle.classList.add('bg-gray-700');
                  dropdownToggle.classList.add('bg-opacity-70');

                  const icon = dropdownToggle.querySelector('.fa-chevron-down');
                  if (icon) icon.classList.add('transform', 'rotate-180');

                  setTimeout(() => {
                      dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px';
                  }, 0);
              }
          }
      }
  });
});