

<div class="panel-navbar">
    <div class="header">
        <div class="title">
            <p>Configuration</p>
        </div>
        <div class="breadcrumb">
            <a href="<?= $_SERVER['REQUEST_URI'] ?>">Panel</a>
            <span class="separator">&gt;</span>
            <a href="<?= routeTo('/panel/dashboard') ?>">Dashboard</a>
        </div>
    </div>
    <div class="search-box">
        <div class="search-content">
            <form method="get">
                <input type="text" class="search" name="search" placeholder="Search" autocomplete="off">
            </form>
            <svg class="search-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.7422 10.3439C12.5329 9.2673 13 7.9382 13 6.5C13 2.91015 10.0899 0 6.5 0C2.91015 0 0 2.91015 0 6.5C0 10.0899 2.91015 13 6.5 13C7.93858 13 9.26801 12.5327 10.3448 11.7415L10.3439 11.7422C10.3734 11.7822 10.4062 11.8204 10.4424 11.8566L14.2929 15.7071C14.6834 16.0976 15.3166 16.0976 15.7071 15.7071C16.0976 15.3166 16.0976 14.6834 15.7071 14.2929L11.8566 10.4424C11.8204 10.4062 11.7822 10.3734 11.7422 10.3439ZM12 6.5C12 9.53757 9.53757 12 6.5 12C3.46243 12 1 9.53757 1 6.5C1 3.46243 3.46243 1 6.5 1C9.53757 1 12 3.46243 12 6.5Z" fill="black" />
            </svg>
        </div>

        <div class="shortcut-icon-box">
            <svg class="shortcut-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 14C3.794 14 2 15.794 2 18C2 20.206 3.794 22 6 22C7.05682 21.9987 8.07025 21.5795 8.81922 20.8339C9.56819 20.0883 9.99193 19.0768 9.998 18.02H10V16H14V18.039H14.004C14.0148 19.0922 14.4404 20.0988 15.1884 20.8403C15.9365 21.5818 16.9467 21.9985 18 22C20.206 22 22 20.206 22 18C22 15.794 20.206 14 18 14H16V10H18C20.206 10 22 8.206 22 6C22 3.794 20.206 2 18 2C15.794 2 14 3.794 14 6V8H10V5.98H9.998C9.99193 4.92319 9.56819 3.91167 8.81922 3.16607C8.07025 2.42047 7.05682 2.0013 6 2C3.794 2 2 3.794 2 6C2 8.206 3.794 10 6 10H8V14H6ZM8 18C8 19.122 7.121 20 6 20C4.879 20 4 19.122 4 18C4 16.878 4.879 16 6 16H8V18ZM18 16C19.121 16 20 16.878 20 18C20 19.122 19.121 20 18 20C16.879 20 16 19.122 16 18V16H18ZM16 6C16 4.878 16.879 4 18 4C19.121 4 20 4.878 20 6C20 7.122 19.121 8 18 8H16V6ZM6 8C4.879 8 4 7.122 4 6C4 4.878 4.879 4 6 4C7.121 4 8 4.878 8 6V8H6ZM10 10H14V14H10V10Z" fill="black" />
            </svg>
            <p class="shortcut-key">M</p>

            <div class="icon-tooltip">
                <p>Ctrl&nbsp;+&nbsp;M</p>
            </div>
        </div>

    </div>
    <div class="profile-box">
        <div class="notifications">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13 3C13 2.73478 12.8946 2.48043 12.7071 2.29289C12.5196 2.10536 12.2652 2 12 2C11.7348 2 11.4804 2.10536 11.2929 2.29289C11.1053 2.48043 11 2.73478 11 3V3.75H10.443C9.37101 3.74993 8.33931 4.15839 7.5579 4.89224C6.7765 5.62608 6.30414 6.63014 6.23698 7.7L6.01598 11.234C5.93171 12.5814 5.47928 13.8799 4.70798 14.988C4.54861 15.2171 4.45124 15.4835 4.42532 15.7613C4.39941 16.0392 4.44584 16.319 4.56009 16.5736C4.67435 16.8281 4.85254 17.0488 5.07735 17.2142C5.30215 17.3795 5.56591 17.4838 5.84298 17.517L9.24998 17.925V19C9.24998 19.7293 9.53971 20.4288 10.0554 20.9445C10.5712 21.4603 11.2706 21.75 12 21.75C12.7293 21.75 13.4288 21.4603 13.9445 20.9445C14.4602 20.4288 14.75 19.7293 14.75 19V17.925L18.157 17.516C18.4339 17.4827 18.6975 17.3784 18.9221 17.2131C19.1468 17.0478 19.3249 16.8273 19.4391 16.5729C19.5534 16.3184 19.5998 16.0388 19.5741 15.7611C19.5483 15.4834 19.4511 15.2171 19.292 14.988C18.5206 13.88 18.0682 12.5815 17.984 11.234L17.763 7.701C17.6961 6.63096 17.2238 5.62665 16.4424 4.8926C15.661 4.15855 14.6291 3.74995 13.557 3.75H13V3ZM10.443 5.25C9.75255 5.24992 9.08805 5.51297 8.58476 5.98561C8.08147 6.45825 7.77723 7.10493 7.73398 7.794L7.51398 11.328C7.41232 12.949 6.8679 14.511 5.93998 15.844C5.92845 15.8606 5.9214 15.8798 5.91952 15.8999C5.91763 15.92 5.92098 15.9403 5.92924 15.9587C5.93749 15.9771 5.95037 15.9931 5.96662 16.005C5.98287 16.017 6.00194 16.0246 6.02198 16.027L9.75898 16.476C11.248 16.654 12.752 16.654 14.241 16.476L17.978 16.027C17.998 16.0246 18.0171 16.017 18.0333 16.005C18.0496 15.9931 18.0625 15.9771 18.0707 15.9587C18.079 15.9403 18.0823 15.92 18.0804 15.8999C18.0786 15.8798 18.0715 15.8606 18.06 15.844C17.1324 14.5109 16.5883 12.9489 16.487 11.328L16.266 7.794C16.2227 7.10493 15.9185 6.45825 15.4152 5.98561C14.9119 5.51297 14.2474 5.24992 13.557 5.25H10.443ZM12 20.25C11.31 20.25 10.75 19.69 10.75 19V18.25H13.25V19C13.25 19.69 12.69 20.25 12 20.25Z" fill="black" />
            </svg>
        </div>
        <div class="identity">
            <div class="profile-name">
                <p><?= $data['username'] ?? 'Unknown' ?></p>
            </div>
            <div class="avatar">
                <img src="" alt="">
            </div>
        </div>
    </div>
</div>

<script>
    const searchBox = document.querySelector('.search-box');
    const searchContent = searchBox.querySelector('.search-content');
    const searchInput = document.querySelector('.search');

    searchInput.addEventListener('submit', () => {
        searchInput.submit();
    });

    window.addEventListener('keydown', (e) => {
        e = e || window.event;

        if (e.ctrlKey && e.key === 'm') {
            searchInput.focus();
            e.preventDefault();
        }
    });

    fetch('<?= routeTo('/api/users/robots') ?>', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            // const profileName = document.querySelector('.profile-name p');
            // const avatar = document.querySelector('.avatar img');

            // profileName.innerHTML = data.name;
            // avatar.src = data.avatar;
            console.log(data);
        })
</script>
