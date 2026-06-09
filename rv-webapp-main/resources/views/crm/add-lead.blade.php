@extends('layouts.crm')

@section('content')
    <div class="crm-panel">
        <div class="lead-hero">
            <div class="hero-profile">
                <div class="hero-avatar">{{ $addLead['lead_avatar_text'] }}</div>
                <div class="hero-copy">
                    <h3>{{ $addLead['hero_title'] }}</h3>
                    <p>{{ $addLead['hero_subtitle'] }}</p>
                    <div class="hero-badges">
                        <span class="status-pill status-pill--blue">{{ $addLead['lead_source_label'] }}</span>
                        <span class="status-pill status-pill--green">{{ $addLead['lead_status_label'] }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-actions">
                <button class="secondary-button" type="button">Insert File</button>
                <button class="success-button" type="button">Assign Project</button>
                <button class="secondary-button" type="button">Add Followup</button>
                <button class="secondary-button" type="button">Upload Documents</button>
            </div>
        </div>
    </div>

    <div class="notice-banner">{{ $addLead['import_status_text'] }}</div>

    <div class="crm-panel" style="margin-top: 18px">
        <h4 style="margin: 0; font-size: 18px">Overview</h4>
        <p class="helper-copy">Enter customer details, requirement context, and lead ownership in the same structured format used by the details page.</p>

        <div class="detail-columns">
            <section class="detail-card">
                <h4>Customer Information</h4>
                <div class="field-list">
                    <div class="field-block full">
                        <span class="field-label">Customer Name</span>
                        <input class="field-input" type="text" value="{{ $addLead['draft']['customer_name'] }}" placeholder="Customer name">
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Mobile</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['mobile'] }}" placeholder="Mobile number">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Email</span>
                            <input class="field-input" type="email" value="{{ $addLead['draft']['email'] }}" placeholder="Email address">
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Company</span>
                        <input class="field-input" type="text" value="{{ $addLead['draft']['company'] }}" placeholder="Company name">
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">City</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['city'] }}" placeholder="City">
                        </div>
                        <div class="field-block">
                            <span class="field-label">State</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['state'] }}" placeholder="State">
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Country</span>
                        <input class="field-input" type="text" value="{{ $addLead['draft']['country'] }}" placeholder="Country">
                    </div>
                </div>
            </section>

            <section class="detail-card">
                <h4>Requirement Details</h4>
                <div class="field-list">
                    <div class="field-block full">
                        <span class="field-label">Requirement Message</span>
                        <textarea class="field-textarea">{{ $addLead['draft']['requirement_message'] }}</textarea>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Product</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['product'] }}" placeholder="Product name">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Quantity</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['quantity'] }}" placeholder="Quantity">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Budget</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['budget'] }}" placeholder="Budget">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Expected Delivery</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['delivery'] }}" placeholder="Expected delivery">
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Category</span>
                        <input class="field-input" type="text" value="{{ $addLead['draft']['category'] }}" placeholder="Category">
                    </div>
                </div>
            </section>

            <section class="detail-card">
                <h4>Lead Information</h4>
                <div class="field-list">
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Lead ID</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['lead_id'] }}" readonly>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Assigned To</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['assigned_to'] }}">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Source</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['source'] }}">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Status</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['status'] }}">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Inquiry Date</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['inquiry_date'] }}">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Created On</span>
                            <input class="field-input" type="text" value="{{ $addLead['draft']['created_on'] }}">
                        </div>
                    </div>
                    <div class="detail-item">
                        <h5>Draft State</h5>
                        <p>Ready for validation before save.</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="detail-grid-two">
            <section class="detail-card">
                <h4>Notes</h4>
                <div class="field-block full" style="margin-bottom: 14px">
                    <span class="field-label">New Note</span>
                    <textarea class="field-textarea" placeholder="Write a note..."></textarea>
                </div>
                <div class="detail-list">
                    @foreach ($addLead['notes'] as $note)
                        <article class="detail-item">
                            <p>{{ $note['text'] }}</p>
                            <div class="detail-meta">
                                <span>{{ $note['author'] }}</span>
                                <span>{{ $note['timestamp'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="detail-card">
                <h4>Documents</h4>
                <p class="description">Uploaded files will appear in Lead Details after saving the lead.</p>
                <div class="detail-list">
                    @foreach ($addLead['documents'] as $document)
                        <article class="detail-item">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px">
                                <strong>{{ $document['file_name'] }}</strong>
                                <span class="status-pill status-pill--slate">{{ $document['file_type'] }}</span>
                            </div>
                            <div class="detail-meta">
                                <span>{{ $document['uploaded_on'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="detail-grid-two">
            <section class="detail-card">
                <h4>Activity Timeline</h4>
                <p class="description">Timeline editing will be enabled later while keeping the normal details-page timeline layout.</p>
                <p class="muted-empty">No timeline events yet for this draft lead.</p>
            </section>

            <section class="detail-card">
                <h4>Add Follow-up</h4>
                <p class="description">Create a reminder row for the Follow Ups page from this draft.</p>
                <div class="field-list">
                    <div class="field-block full">
                        <span class="field-label">Subject</span>
                        <input class="field-input" type="text" value="{{ $addLead['follow_up']['subject'] }}">
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Date</span>
                            <input class="field-input" type="text" value="{{ $addLead['follow_up']['date'] }}">
                        </div>
                        <div class="field-block">
                            <span class="field-label">Time</span>
                            <input class="field-input" type="text" value="{{ $addLead['follow_up']['time'] }}">
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Company Name</span>
                        <input class="field-input" type="text" value="{{ $addLead['follow_up']['company_name'] }}">
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
