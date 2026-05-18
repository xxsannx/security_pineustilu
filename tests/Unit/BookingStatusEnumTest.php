<?php

namespace Tests\Unit;

use App\Enums\BookingStatus;
use PHPUnit\Framework\TestCase;

class BookingStatusEnumTest extends TestCase
{
    public function test_all_statuses_have_labels(): void
    {
        foreach (BookingStatus::cases() as $status) {
            $this->assertNotEmpty($status->label(), "Status {$status->name} should have a label");
        }
    }

    public function test_all_statuses_have_colors(): void
    {
        foreach (BookingStatus::cases() as $status) {
            $this->assertNotEmpty($status->color(), "Status {$status->name} should have a color");
        }
    }

    public function test_all_statuses_have_icons(): void
    {
        foreach (BookingStatus::cases() as $status) {
            $this->assertNotEmpty($status->icon(), "Status {$status->name} should have an icon");
        }
    }

    public function test_options_returns_all_statuses(): void
    {
        $options = BookingStatus::options();
        
        $this->assertIsArray($options);
        $this->assertCount(count(BookingStatus::cases()), $options);
        
        // Check all status values are present as keys
        foreach (BookingStatus::cases() as $status) {
            $this->assertArrayHasKey($status->value, $options);
        }
    }

    public function test_proses_status_can_be_cancelled(): void
    {
        $this->assertTrue(BookingStatus::PROSES->canBeCancelled());
    }

    public function test_pembayaran_status_can_be_cancelled(): void
    {
        $this->assertTrue(BookingStatus::PEMBAYARAN->canBeCancelled());
    }

    public function test_berhasil_status_can_be_cancelled(): void
    {
        $this->assertTrue(BookingStatus::BERHASIL->canBeCancelled());
    }

    public function test_berjalan_status_cannot_be_cancelled(): void
    {
        $this->assertFalse(BookingStatus::BERJALAN->canBeCancelled());
    }

    public function test_selesai_status_cannot_be_cancelled(): void
    {
        $this->assertFalse(BookingStatus::SELESAI->canBeCancelled());
    }

    public function test_dibatalkan_status_cannot_be_cancelled(): void
    {
        $this->assertFalse(BookingStatus::DIBATALKAN->canBeCancelled());
    }

    public function test_berhasil_status_can_be_rescheduled(): void
    {
        $this->assertTrue(BookingStatus::BERHASIL->canBeRescheduled());
    }

    public function test_proses_status_cannot_be_rescheduled(): void
    {
        $this->assertFalse(BookingStatus::PROSES->canBeRescheduled());
    }

    public function test_selesai_status_cannot_be_rescheduled(): void
    {
        $this->assertFalse(BookingStatus::SELESAI->canBeRescheduled());
    }

    public function test_status_labels_are_in_english(): void
    {
        $this->assertEquals('Processing', BookingStatus::PROSES->label());
        $this->assertEquals('Awaiting Payment', BookingStatus::PEMBAYARAN->label());
        $this->assertEquals('Confirmed', BookingStatus::BERHASIL->label());
        $this->assertEquals('In Progress', BookingStatus::BERJALAN->label());
        $this->assertEquals('Completed', BookingStatus::SELESAI->label());
        $this->assertEquals('Cancelled', BookingStatus::DIBATALKAN->label());
    }
}
