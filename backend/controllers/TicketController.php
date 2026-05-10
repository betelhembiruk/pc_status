public static function createTicket($conn, $data)
        $priority = 'Medium';
        $slaDays = 3;
        $status = 'Pending';

        if (!$serialNumber || !$branch || !$problem) {
            return [
                'success' => false,
                'message' => 'Required fields missing'
            ];
        }

        $createdBy = $_SESSION['user']['id'];

        $stmt = $conn->prepare(
            'INSERT INTO tickets (
                serialNumber,
                tagNumber,
                pcModel,
                branch,
                issue,
                phone,
                broughtBy,
                hardwareType,
                status,
                priority,
                slaDays,
                created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            return [
                'success' => false,
                'message' => $conn->error
            ];
        }

        $stmt->bind_param(
            'ssssssssssii',
            $serialNumber,
            $tagNumber,
            $pcModel,
            $branch,
            $problem,
            $phone,
            $broughtBy,
            $hardwareType,
            $status,
            $priority,
            $slaDays,
            $createdBy
        );

        if (!$stmt->execute()) {
            return [
                'success' => false,
                'message' => $stmt->error
            ];
        }

        return [
            'success' => true,
            'ticket_id' => $conn->insert_id,
            'message' => 'Ticket created successfully'
        ];

    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}