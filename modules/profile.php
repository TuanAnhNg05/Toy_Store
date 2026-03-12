<style>
    .profile-container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        display: flex;
    }

    .profile-sidebar {
        background: linear-gradient(135deg, #FF6F00, #FF8F00);
        width: 300px;
        padding: 40px 20px;
        color: white;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .user-avatar-big {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #FF6F00;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .user-name-display {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .user-email-display {
        font-size: 14px;
        opacity: 0.8;
    }

    .profile-content {
        flex: 1;
        padding: 40px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 800;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px dashed #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
    }

    .custom-input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 2px solid #f0f0f0;
        background: #fcfcfc;
        font-family: 'Quicksand', sans-serif;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .custom-input:focus {
        border-color: #FF6F00;
        background: white;
        outline: none;
    }

    .btn-save {
        background: #FF6F00;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save:hover {
        background: #E65100;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-error {
        background: #FFEBEE;
        color: #C62828;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .profile-container {
            flex-direction: column;
            margin: 20px;
        }

        .profile-sidebar {
            width: 100%;
            padding: 30px;
        }
    }
</style>

<div class="container">
    <div class="profile-container">

        <div class="profile-sidebar">
            <div class="user-avatar-big">
                <i class="fa fa-user"></i>
            </div>
            <div class="user-name-display"><?= $data['user']['fullname'] ?></div>
            <div class="user-email-display"><?= $data['user']['email'] ?></div>
            <div style="margin-top: 20px; font-size: 13px; opacity: 0.7;">
                Thành viên từ: <?= date('d/m/Y', strtotime($data['user']['created_at'] ?? 'now')) ?>
            </div>
        </div>

        <div class="profile-content">
            <h2 class="section-title">Cập nhật thông tin</h2>

            <?= isset($data['msg']) ? $data['msg'] : '' ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Họ và tên:</label>
                    <input type="text" name="fullname" class="custom-input" value="<?= $data['user']['fullname'] ?>"
                        required placeholder="Nhập tên hiển thị...">
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại:</label>
                    <input type="text" name="phone" class="custom-input" value="<?= $data['user']['phone'] ?? '' ?>"
                        placeholder="Số điện thoại nhận hàng...">
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ giao hàng mặc định:</label>
                    <input type="text" name="address" class="custom-input" value="<?= $data['user']['address'] ?? '' ?>"
                        placeholder="Địa chỉ giao hàng...">
                </div>

                <div class="form-group">
                    <label class="form-label">Đổi mật khẩu (Bỏ trống nếu không đổi):</label>
                    <input type="password" name="password" class="custom-input" placeholder="Nhập mật khẩu mới...">
                </div>

                <button type="submit" class="btn-save">
                    <i class="fa fa-save"></i> LƯU THAY ĐỔI
                </button>
            </form>
        </div>

    </div>
</div>